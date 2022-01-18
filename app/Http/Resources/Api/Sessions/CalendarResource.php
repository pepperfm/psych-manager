<?php

namespace App\Http\Resources\Api\Sessions;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Request;

use App\Models\Session;

/** @mixin Session */
class CalendarResource extends JsonResource
{
    /**
     * @param Request $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'session_date' => $this->session_date,
            'session_time' => Carbon::parse($this->session_date_calendar)->addHour()->format('H:i'),
            'start' => $this->session_date_calendar,
            'end' => Carbon::parse($this->session_date_calendar)->addHour()->format('Y-m-d H:i'),
            'title' => $this->whenLoaded('client')?->name,
            'content' => '',
            'class' => 'leisure',
            'comment' => $this->comment,
            'session_date_seconds' => $this->session_date_seconds,

            'name' => $this->whenLoaded('client')?->name,
            'phone' => $this->whenLoaded('client')?->phone,
            'connection_type_string' => $this->whenLoaded('client')->connectionType?->name,
            'connection_type_link' => $this->whenLoaded('client')->connection_type_link,
            'meeting_type_icon' => $this->whenLoaded('client')->meeting_type ? 'el-icon-office-building' : 'el-icon-service',
        ];
    }
}
