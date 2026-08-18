<?php

namespace App\Models;

use Database\Factories\CompanyProfileFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanyProfile extends Model
{
    /** @use HasFactory<CompanyProfileFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'tagline_id',
        'tagline_en',
        'about_id',
        'about_en',
        'vision_id',
        'vision_en',
        'mission_id',
        'mission_en',
        'address',
        'phone',
        'whatsapp',
        'email',
        'maps_embed_url',
    ];

    /**
     * Get the localized tagline.
     */
    public function tagline(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->tagline_en)
                ? $this->tagline_en
                : $this->tagline_id,
        );
    }

    /**
     * Get the localized about description.
     */
    public function about(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->about_en)
                ? $this->about_en
                : $this->about_id,
        );
    }

    /**
     * Get the localized vision statement.
     */
    public function vision(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->vision_en)
                ? $this->vision_en
                : $this->vision_id,
        );
    }

    /**
     * Get the localized mission statement.
     */
    public function mission(): Attribute
    {
        return Attribute::make(
            get: fn (): string => app()->getLocale() === 'en' && ! empty($this->mission_en)
                ? $this->mission_en
                : $this->mission_id,
        );
    }
}
