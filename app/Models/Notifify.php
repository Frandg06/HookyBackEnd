<?php


namespace App\Models;

use Illuminate\Support\Facades\Http;

class Notifify
{
  public $attributes = [];

  const LIKE = 1;
  const SUPER_LIKE = 2;
  const HOOK = 3;
  const MESSAGE = 4;
  const LIKE_STR = 'like';
  const SUPER_LIKE_STR = 'superlike';
  const HOOK_STR = 'hook';
  const MESSAGE_STR = 'message';

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
      'Authorization' => 'Bearer ' . request()->bearerToken(),
      'Accept' => 'application/json'
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
      self::LIKE => __('i18n.notify.base', ['interaction' => SELF::LIKE_STR]),
      self::SUPER_LIKE => __('i18n.notify.base', ['interaction' => SELF::SUPER_LIKE_STR]),
      self::HOOK => __('i18n.notify.hook'),
      self::MESSAGE => __('i18n.notify.message', ['username' => $this->sender_name]),
    };
  }

  private function getTypeStr()
  {
    return match ($this->type_id) {
      self::LIKE => SELF::LIKE_STR,
      self::SUPER_LIKE => SELF::SUPER_LIKE_STR,
      self::HOOK => SELF::HOOK_STR,
      self::MESSAGE => SELF::MESSAGE_STR,
    };
  }
}
