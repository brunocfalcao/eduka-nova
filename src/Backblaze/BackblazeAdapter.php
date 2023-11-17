<?php

namespace Eduka\Nova\Backblaze;

use BackblazeB2\Client;
use GuzzleHttp\Psr7;
use League\Flysystem\Adapter\AbstractAdapter;
use League\Flysystem\Adapter\Polyfill\NotSupportingVisibilityTrait;
use League\Flysystem\Config;

class BackblazeAdapter extends AbstractAdapter
{
    use NotSupportingVisibilityTrait;

    protected Client $client;

    protected string $bucketName;

    protected ?string $bucketId;

    public function __construct(Client $client, string $bucketName, string $bucketId = null)
    {
        $this->client = $client;
        $this->bucketName = $bucketName;
        $this->bucketId = $bucketId;
    }

    public function has(string $path): bool
    {
        return $this->getClient()->fileExists(['FileName' => $path, 'BucketId' => $this->bucketId, 'BucketName' => $this->bucketName]);
    }

    /**
     * {@inheritdoc}
     */
    public function write($path, $contents, Config $config)
    {
        $file = $this->getClient()->upload([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
            'Body' => $contents,
        ]);

        return $this->getFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    public function writeStream($path, $resource, Config $config)
    {
        $file = $this->getClient()->upload([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
            'Body' => $resource,
        ]);

        return $this->getFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    public function update($path, $contents, Config $config)
    {
        $file = $this->getClient()->upload([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
            'Body' => $contents,
        ]);

        return $this->getFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    public function updateStream($path, $resource, Config $config)
    {
        $file = $this->getClient()->upload([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
            'Body' => $resource,
        ]);

        return $this->getFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    public function read($path)
    {
        $file = $this->getClient()->getFile([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
        ]);
        $fileContent = $this->getClient()->download([
            'FileId' => $file->getId(),
        ]);

        return ['contents' => $fileContent];
    }

    /**
     * {@inheritdoc}
     */
    public function readStream($path)
    {
        $stream = Psr7\stream_for();
        $download = $this->getClient()->download([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
            'SaveAs' => $stream,
        ]);
        $stream->seek(0);

        try {
            $resource = Psr7\StreamWrapper::getResource($stream);
        } catch (InvalidArgumentException $e) {
            return false;
        }

        return $download === true ? ['stream' => $resource] : false;
    }

    /**
     * {@inheritdoc}
     */
    public function rename($path, $newpath)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function copy($path, $newPath)
    {
        return $this->getClient()->upload([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $newPath,
            'Body' => @file_get_contents($path),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($path)
    {
        return $this->getClient()->deleteFile(['FileName' => $path, 'BucketId' => $this->bucketId, 'BucketName' => $this->bucketName]);
    }

    /**
     * {@inheritdoc}
     */
    public function deleteDir($path)
    {
        return $this->getClient()->deleteFile(['FileName' => $path, 'BucketId' => $this->bucketId, 'BucketName' => $this->bucketName]);
    }

    /**
     * {@inheritdoc}
     */
    public function createDir($path, Config $config)
    {
        return $this->getClient()->upload([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
            'FileName' => $path,
            'Body' => '',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getMetadata($path)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getMimetype($path)
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function getSize($path)
    {
        $file = $this->getClient()->getFile(['FileName' => $path, 'BucketId' => $this->bucketId, 'BucketName' => $this->bucketName]);

        return $this->getFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    public function getTimestamp($path)
    {
        $file = $this->getClient()->getFile(['FileName' => $path, 'BucketId' => $this->bucketId, 'BucketName' => $this->bucketName]);

        return $this->getFileInfo($file);
    }

    /**
     * {@inheritdoc}
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function listContents($directory = '', $recursive = false)
    {
        $fileObjects = $this->getClient()->listFiles([
            'BucketId' => $this->bucketId,
            'BucketName' => $this->bucketName,
        ]);
        if ($recursive === true && $directory === '') {
            $regex = '/^.*$/';
        } elseif ($recursive === true && $directory !== '') {
            $regex = '/^'.preg_quote($directory).'\/.*$/';
        } elseif ($recursive === false && $directory === '') {
            $regex = '/^(?!.*\\/).*$/';
        } elseif ($recursive === false && $directory !== '') {
            $regex = '/^'.preg_quote($directory).'\/(?!.*\\/).*$/';
        } else {
            throw new \InvalidArgumentException();
        }
        $fileObjects = array_filter($fileObjects, function ($fileObject) use ($regex) {
            return preg_match($regex, $fileObject->getName()) === 1;
        });
        $normalized = array_map(function ($fileObject) {
            return $this->getFileInfo($fileObject);
        }, $fileObjects);

        return array_values($normalized);
    }

    protected function getFileInfo($file): array
    {
        return [
            'type' => 'file',
            'path' => $file->getName(),
            'timestamp' => substr($file->getUploadTimestamp(), 0, -3),
            'size' => $file->getSize(),
        ];
    }
}
