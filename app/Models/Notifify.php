<?php

namespace App\Models;

use Illuminate\Support\Facades\Http;

class Notifify
{
    public $attributes = [];

    public const LIKE = 1;

    public const SUPER_LIKE = 2;

    public const HOOK = 3;

    public const MESSAGE = 4;

    public const LIKE_STR = 'like';

    public const SUPER_LIKE_STR = 'superlike';

    public const HOOK_STR = 'hook';

    public const MESSAGE_STR = 'message';

    public function __construct($attributes = [])
    {
        $this->attributes = $attributes;
        $this->message = $this->getMessage();
        $this->type_name = $this->getTypeStr();
    }

    public function __get($name)
    {
        return $this->attributes[$name] ?? null;
    }

    public function __set($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    public function __isset($name)
    {
        return isset($this->attributes[$name]);
    }

    public function toArray()
    {
        return $this->attributes;
    }

    public function dualEmit()
    {
        $this->emit();
        $this->getReverse();
        $this->emit();
    }

    public function dualEmitWithSave()
    {
        $this->emit();
        $this->save();
        $this->getReverse();
        $this->emit();
        $this->save();
    }

    public function save()
    {
        Notification::create([
            'event_uid' => request()->user()->event->uid,
            'user_uid' => $this->reciber_uid,
            'emitter_uid' => $this->sender_uid,
            'type_id' => $this->type_id,
        ]);
    }

    public function emit()
    {
        $url = config('services.ws_api.notify_url');

        Http::withHeaders([
            'Authorization' => 'Bearer '.request()->bearerToken(),
            'Accept' => 'application/json',
        ])->post($url, $this->toArray());
    }

    public function getReverse()
    {
        $reversed = $this->attributes;

        $reversed['sender_uid'] = $this->reciber_uid;
        $reversed['reciber_uid'] = $this->sender_uid;

        $this->attributes = $reversed;
    }

    private function getMessage()
    {
        return match ($this->type_id) {
            self::LIKE => __('i18n.notify.base', ['interaction' => self::LIKE_STR]),
            self::SUPER_LIKE => __('i18n.notify.base', ['interaction' => self::SUPER_LIKE_STR]),
            self::HOOK => __('i18n.notify.hook'),
            self::MESSAGE => __('i18n.notify.message', ['username' => $this->sender_name]),
        };
    }

    private function getTypeStr()
    {
        return match ($this->type_id) {
            self::LIKE => self::LIKE_STR,
            self::SUPER_LIKE => self::SUPER_LIKE_STR,
            self::HOOK => self::HOOK_STR,
            self::MESSAGE => self::MESSAGE_STR,
        };
    }
}
