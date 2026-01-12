<?php

namespace App\Helpers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;
use Kreait\Laravel\Firebase\Facades\Firebase;

class Helper
{
        const CHUNK_SIZE = 5 * 1024 * 1024;

    //! File or Image Upload
    public static function fileUpload($file, string $folder, string $name): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $imageName = Str::slug($name) . '.' . $file->extension();
        $path      = public_path('uploads/' . $folder);
        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }
        $file->move($path, $imageName);
        return 'uploads/' . $folder . '/' . $imageName;
    }

    //! File or Image Delete
    public static function fileDelete(string $path): void
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

    //! Generate Slug
    public static function makeSlug($model, string $title): string
    {
        $slug = Str::slug($title);
        while ($model::where('slug', $slug)->exists()) {
            $randomString = Str::random(5);
            $slug         = Str::slug($title) . '-' . $randomString;
        }
        return $slug;
    }

    //! JSON Response
    public static function jsonResponse(bool $status, string $message, int $code, $data = null, bool $paginate = false, $paginateData = null): JsonResponse
    {
        $response = [
            'status'  => $status,
            'message' => $message,
            'code'    => $code,
        ];
        if ($paginate && !empty($paginateData)) {
            $response['data'] = $data;
            $response['pagination'] = [
                'current_page' => $paginateData->currentPage(),
                'last_page' => $paginateData->lastPage(),
                'per_page' => $paginateData->perPage(),
                'total' => $paginateData->total(),
                'first_page_url' => $paginateData->url(1),
                'last_page_url' => $paginateData->url($paginateData->lastPage()),
                'next_page_url' => $paginateData->nextPageUrl(),
                'prev_page_url' => $paginateData->previousPageUrl(),
                'from' => $paginateData->firstItem(),
                'to' => $paginateData->lastItem(),
                'path' => $paginateData->path(),
            ];
        } elseif ($paginate && !empty($data)) {
            $response['data'] = $data->items();
            $response['pagination'] = [
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'per_page' => $data->perPage(),
                'total' => $data->total(),
                'first_page_url' => $data->url(1),
                'last_page_url' => $data->url($data->lastPage()),
                'next_page_url' => $data->nextPageUrl(),
                'prev_page_url' => $data->previousPageUrl(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
                'path' => $data->path(),
            ];
        } elseif ($data !== null) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }

    public static function jsonErrorResponse(string $message, int $code = 400, array $errors = []): JsonResponse
    {
        $response = [
            'status'  => false,
            'message' => $message,
            'code'    => $code,
            't-errors'  => $errors,
        ];
        return response()->json($response, $code);
    }





    public static function video($file, string $folder, string $name): ?string
    {
        if (!$file->isValid()) {
            return null;
        }

        $fileSize = $file->getSize();

        // If file is larger than 5MB, use chunk upload
        if ($fileSize > self::CHUNK_SIZE) {
            return self::chunkFileUpload($file, $folder, $name);
        }

        // Regular upload for files <= 5MB
        return self::regularFileUpload($file, $folder, $name);
    }

    private static function regularFileUpload($file, string $folder, string $name): ?string
    {
        $imageName = Str::slug($name) . '.' . $file->extension();
        $path      = public_path('uploads/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $file->move($path, $imageName);
        return 'uploads/' . $folder . '/' . $imageName;
    }

    private static function chunkFileUpload($file, string $folder, string $name): ?string
    {
        $fileName = Str::slug($name) . '.' . $file->extension();
        $path = public_path('uploads/' . $folder);

        if (!file_exists($path)) {
            mkdir($path, 0777, true);
        }

        $destination = $path . '/' . $fileName;
        $source = fopen($file->getRealPath(), 'rb');
        $dest = fopen($destination, 'wb');

        if (!$source || !$dest) {
            return null;
        }

        try {
            // Read and write in chunks
            while (!feof($source)) {
                $chunk = fread($source, self::CHUNK_SIZE);
                fwrite($dest, $chunk);

                // Free up memory
                unset($chunk);
            }

            fclose($source);
            fclose($dest);

            return 'uploads/' . $folder . '/' . $fileName;

        } catch (\Exception $e) {
            // Clean up on error
            if (is_resource($source)) fclose($source);
            if (is_resource($dest)) fclose($dest);
            if (file_exists($destination)) unlink($destination);

            return null;
        }
    }

     public static function sendNotifyMobile($token, $notifyData): void
      {
          try {
              $messaging = Firebase::messaging();

              $notification = Notification::create(
                  $notifyData['title'],
                  Str::limit($notifyData['body'], 100),
                  $notifyData['icon']
              );

              $message = CloudMessage::withTarget('token', $token)
                  ->withNotification($notification);

              $messaging->send($message);

          } catch (\Throwable $e) {
              Log::error($e->getMessage());
          }
      }

}
