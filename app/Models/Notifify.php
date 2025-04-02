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
