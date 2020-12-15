<?php

namespace Belyaevad\SeoTextable;

use Belyaevad\SeoTextable\Models\SeoTextable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class SeoTextableController extends Controller
{
    public function readList($limit = 10)
    {
        if ( ! $this->CheckAuth()) {
            return response('Unauthorized', 401);
        }

        $list = SeoTextable::whereHasRead(0)->with('textable')->limit($limit)
            ->get();
        $ret = [];

        if ($list->count()) {
            foreach ($list as $item) {
                if (isset($item->textable)) {
                    $ret[$item->id] = $item->textable->getClearText();
                }
            }
        }

        return base64_encode(json_encode($ret));

    }

    private function CheckAuth()
    {
        $key = \request()->get('token', false);

        return $key == config('seotextable.token');
    }

    public function HasRead(Request $request)
    {
        if ( ! $this->CheckAuth()) {
            return response('Unauthorized', 401);
        }

        $ids = $request->get('HasRead');

        if ($ids) {
            $ids = json_decode(base64_decode($ids), true);

            if ($ids && is_array($ids) && sizeof($ids)) {
                SeoTextable::whereIn('id', $ids)->update(['has_read' => true]);
            }

            return response('ok');
        }

        return response('error', 400);
    }

    public function SetLinks(Request $request)
    {
        if ( ! $this->CheckAuth()) {
            return response('Unauthorized', 401);
        }

        $data = $request->request->get('SetLinks');
        if ($data) {
            $data = json_decode(base64_decode($data), true);

            if ($data && is_array($data) && sizeof($data)) {
                $ok_list = [];
                foreach ($data as $key => $item) {
                    if (isset($item['id']) && isset($item['links'])) {
                        $up = SeoTextable::whereId($item['id'])->update([
                            'has_links' => true,
                            'links'     => $item['links'],
                        ]);

                        if ($up) {
                            $ok_list[$key] = $item['id'];
                        }
                    }

                }

                return response()->json($ok_list);
            }
        }

        return response('error', 400);
    }

    public function StartUpdate()
    {
        if ( ! $this->CheckAuth()) {
            return response('Unauthorized', 401);
        }

        $up = SeoTextable::whereHasRead(1)->update([
            'has_read' => false,
        ]);

        return response('Ok update: '.$up);
    }

    public function DeleteData()
    {
        if ( ! $this->CheckAuth()) {
            return response('Unauthorized', 401);
        }

        $up = SeoTextable::update([
            'has_read' => false,
            'has_links' => false,
            'links' => '',
        ]);

        return response('Ok Clear: '.$up);
    }

    public function GetStat()
    {
        if ( ! $this->CheckAuth()) {
            return response('Unauthorized', 401);
        }

        $data = SeoTextable::select('has_read', 'has_links')->get();

        $ret['not_read'] = $data->where('has_read', 0)->count();
        $ret['has_read'] = $data->where('has_read', 1)->count();
        $ret['links_count'] = $data->where('has_links', 0)->count();
        $ret['total'] = $ret['has_read'] + $ret['not_read'];
        $ret['sample'] = ['samples disabled'];

        return response()->json($ret);
    }
}
