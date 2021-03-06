<?php

namespace app\index\controller;

use think\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->assign('menu', 'index');
        $this->assign('title', input('title'));
        $this->assign('menu', 'index');
        return $this->fetch();
    }

    public function openImage()
    {
        $file = request()->file('file');
        $info = $file->move('upload'); //给定一个目录
        if ($info && $info->getPathname()) {
            return json([
                'status' => 1,
                'message' => 'success',
                'src' => config('setting.img_prefix') . $info->getPathname(),
                'thumb' => config('setting.img_prefix') . $info->getPathname()
            ]);
        } else {
            return json([
                'status' => 0,
                'message' => 'error',
            ]);
        }
    }

    public function openTxt()
    {
        $file = request()->file('file');
        $info = $file->move('upload'); //给定一个目录
        if ($info && $info->getPathname()) {
            $thumb = $info->getPathname();
            $content = file_get_contents($thumb);//将整个文件内容读入到一个字符串中
            
            return json([
                'status' => 1,
                'message' => 'success',
                'content' => json_decode($content)
            ]);
        } else {
            return json([
                'status' => 0,
                'message' => 'error',
            ]);
        }
    }

    public function save()
    {
        if (!session('user') || session('user') == null) {
            return json(['status' => 0, 'message' => '请先登录']);
        }
        $data = validate('Draw')->goCheck('add');
        if (!is_array($data)) {
            $d = request()->param();
            $d['user_id'] = session('user')['id'];
            $d['status'] = 1;
            $d['create_time'] = time();
            $d['update_time'] = $d['create_time'];
            $id = model('Draw')->create($d);
            if ($id) {
                return json(['status' => 1, 'message' => '保存成功']);
            } else {
                return json(['status' => 0, 'message' => '保存失败']);
            }
        } else {
            return json($data);
        }
    }

    public function image()
    {
        if (!session('user') || session('user') == null) {
            return json(['status' => 0, 'message' => '请先登录']);
        }
        $file = request()->file('file');
        $info = $file->move('upload'); //给定一个目录
        if ($info && $info->getPathname()) {
            return json([
                'status' => 1,
                'message' => 'success',
                'thumb' => config('setting.img_prefix') . $info->getPathname(),
                'src' => config('setting.img_prefix') . $info->getPathname()
            ]);
        } else {
            return json([
                'status' => 0,
                'message' => 'error',
            ]);
        }
    }

    public function draw()
    {
        $id = input('id');
        $this->assign('id', $id);
        $this->assign('menu', 'index');
        $this->assign('title', input('title'));
        $this->assign('menu', 'index');
        return $this->fetch();
    }
}
