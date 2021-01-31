<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Fillable fields
     * 
     * @var array
     */
    protected $fillable = [
		'original_file_name',
		'file_path',
    ];

    /**
     * Properties to append to the model's object
     * 
     * @var array
     */
    protected $appends = [
        'download_link',
    ];

    /**
     * Accessor for the download link
     *
     * @return String Download URL
     */
    public function getDownloadLinkAttribute()
    {
        return Storage::url($this->file_path);
    }

    /**
     * Format response for API
     *
     * @return array
     */
    public function formatForApi()
    {
        return  [
            "attachmentId"  => $this->id,
            "fileName"      => $this->original_file_name,
            "filePath"      => $this->file_path,
            "downloadLink"  => $this->download_link,
            "createdAt"     => $this->created_at,
        ];
    }
}
