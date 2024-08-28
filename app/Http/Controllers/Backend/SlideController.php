<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Repositories\SlideRepository;
use App\Services\SlideService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class SlideController extends Controller
{
    protected $slideService;
    protected $slideRepository;

    public function __construct(SlideService $slideService, SlideRepository $slideRepository)
    {
        $this->slideService = $slideService;
        $this->slideRepository = $slideRepository;
    }

    public function index(Request $request)
    {
        Gate::authorize('modules', 'slide.index');
        $slides = $this->slideService->paginate($request);
        $config = [
            'js' => [
                'backend/js/plugins/switchery/switchery.js',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js'
            ],
            'css' => [
                'backend/css/plugins/switchery/switchery.css',
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'model' => 'Slide'
        ];
        $config['seo'] = __('slide');

        $template = 'backend.slide.slide.index';
        return view('backend.dashboard.layout', compact('template', 'config', 'slides'));
    }

    public function create()
    {
        Gate::authorize('modules', 'slide.create');
        $config = $this->configData();
        $config['seo'] = __('slide');
        $config['method'] = 'create';
        $template = 'backend.slide.slide.store';
        return view('backend.dashboard.layout', compact('template', 'config'));
    }

    public function store(StoreSlideRequest $storeSlideRequest)
    {
        if ($this->slideService->create($storeSlideRequest)) {
            flash()->success(__('toast.store_success'));
            return redirect()->route('slide.index');
        }
        flash()->error(__('toast.store_failed'));
        return redirect()->route('slide.index');
    }

    public function edit($id)
    {
        Gate::authorize('modules', 'slide.update');
        $slide = $this->slideRepository->findById($id);
        $config = $this->configData();
        $config['seo'] = __('slide');
        $config['method'] = 'edit';
        $template = 'backend.slide.slide.store';
        return view('backend.dashboard.layout', compact('template', 'config', 'slide'));
    }

    public function update($id, UpdateSlideRequest $updateSlideRequest)
    {
        if ($this->slideService->update($id, $updateSlideRequest)) {
            flash()->success(__('toast.update_success'));
            return redirect()->route('slide.index');
        }
        flash()->error(__('toast.update_failed'));
        return redirect()->route('slide.index');
    }

    public function delete($id)
    {
        Gate::authorize('modules', 'slide.destroy');
        $slide = $this->slideRepository->findById($id);
        $config['seo'] = __('slide');
        $template = 'backend.slide.slide.delete';
        return view('backend.dashboard.layout', compact('template', 'slide', 'config'));
    }

    public function destroy($id)
    {
        if ($this->slideService->delete($id)) {
            flash()->success(__('toast.destroy_success'));
            return redirect()->route('slide.index');
        }
        flash()->error(__('toast.destroy_failed'));
        return redirect()->route('slide.index');
    }

    private function configData()
    {
        return [
            'css' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css'
            ],
            'js' => [
                'https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js',
                'backend/plugins/ckfinder_2/ckfinder.js',
                'backend/library/slide.js',
            ]
        ];
    }
}
