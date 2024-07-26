<?php

namespace App\Http\Controllers\Dashboard\Structure;

use App\Http\Controllers\Controller;
use App\Http\Services\Mutual\FileManagerService;
use App\Repository\StructureRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

abstract class ContentController extends Controller
{
    protected string $contentKey;
    protected array $locales;

    protected StructureRepositoryInterface $structureRepository;
    protected FileManagerService $fileManager;

    public function __construct(
        StructureRepositoryInterface $structureRepository,
        FileManagerService $fileManagerService,
    )
    {
        $this->middleware('auth');
        $this->middleware('permission:structure-read')->only('index');
        $this->middleware('permission:structure-update')->except('index');
        $this->structureRepository = $structureRepository;
        $this->fileManager = $fileManagerService;
    }

    public function index()
    {
        $content = json_decode($this->structureRepository->structure($this->contentKey)->content, true);
        return view('dashboard.site.structures.content.'.$this->contentKey, compact('content'));
    }

    public function _store(Request $request)
    {
        $content = $this->build($request);
        $this->structureRepository->publish($this->contentKey, $content);
        return redirect()->back()->with('success', __('messages.Content published successfully'));
    }

    private function build($request) {
        $data = $this->file($request);
        $result = [];
        foreach ($data as $locale => $content) {
            if(in_array($locale, $this->locales)) {
                $result[$locale] = $content;
                if (isset($data['all'])) {
                    $result[$locale] = array_merge_recursive_distinct($result[$locale], $data['all']);
                }
            }
        }
        return json_encode(safeArray($result));
    }

    private function file($request) {
        $data = $request->all();
        if (isset($data['old_file'])){
            if (is_array($data['old_file'])) {
                foreach ($data['old_file'] as $i => $oldFile) {
                    $oldFilePath = str_replace(url('/'), '', $oldFile);
                    if(is_array($request->file('file')) && isset($request->file('file')[$i])) {
                        $this->fileManager->deleteFile($oldFilePath);
                        $filePath = $this->fileManager->upload('file.'.$i, 'content/'.$this->contentKey);
                        $this->assignFilesUrls($data, 'file_'.$i, url($filePath));
                    } else {
                        $this->assignFilesUrls($data, 'file_'.$i, url($oldFilePath));
                    }
                }
            }
        }
        return $data;
    }

    private function assignFilesUrls(&$data, $search, $replace)
    {
        foreach ($data as &$value) {
            if (is_array($value)) {
                $this->assignFilesUrls($value, $search, $replace);
            } elseif ($value == $search) {
                $value = $replace;
            }
        }
    }

}
