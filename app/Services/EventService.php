<?php

namespace App\Services;

use App\Models\Event;
use App\Repositories\EventRepository;

class EventService extends BaseService
{
    public function __construct(
        protected EventRepository $repository,
        protected FileService $fileService
    ) {
    }

    public function save($request, Event $event)
    {
        $request['poster_path'] = $this->fileService->save(
            $request,
            'poster',
            'events/posters',
            'public'
        );

        $request['backdrop_path'] = $this->fileService->save(
            $request,
            'backdrop',
            'events/backdrops',
            'public'
        );

        $event->fill($request->only($event->getFillable()));
        $event->save();
        $event->genres()->sync($request->input('genres'));

        return ['message' => 'success'];
    }

    public function update($request, Event $event)
    {
        if ($request->hasFile('poster')) {

            $this->fileService->delete('public', $event->poster_path);
            $request['poster_path'] = $this->fileService->save(
                $request,
                'poster',
                'events/posters',
                'public'
            );
        }

        if ($request->hasFile('backdrop')) {

            $this->fileService->delete('public', $event->backdrop_path);
            $request['backdrop_path'] = $this->fileService->save(
                $request,
                'backdrop',
                'events/backdrops',
                'public'
            );
        }

        $event->fill($request->only($event->getFillable()));
        $event->save();
        $event->genres()->sync($request->input('genres'));

        return ['message' => 'success'];
    }
    public function delete(Event $event)
    {
        $this->fileService->delete('public', $event->poster_path);
        $this->fileService->delete('public', $event->backdrop_path);

        return $event->delete();
    }
}
