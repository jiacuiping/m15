<?php 

namespace app\admin\controller;

use think\request;

/**
 *	文件上传控制器（Justin：2019-03-12）

 *	ControllerList
 *	UploadImage			上传图片
 *	UploadIcon			上传图标
 *	UploadFile			上传文件
 *	DeleteImage			删除上传的图片
 *	DeleteIcon			删除上传的图标
 *	DeleteFile			删除上传的文件
 */

class Upload extends LoginBase
{
	//上传图片
	public function UploadImage($path='uploads')
	{
        // 获取表单上传文件 例如上传了001.jpg
        $file = request()->file('file');
        
        // 移动到框架应用根目录/public/uploads/ 目录下
        if($file){
            $info = $file->move(ROOT_PATH . 'public' . DS . $path);

            return $info ? array('code'=>1,'msg'=>'上传成功','path'=>'/'.$path.'/'.$info->getSaveName()) : array('code'=>0,'msg'=>$file->getError());
        }
	}

	//上传图标
	public function UploadIcon()
	{

	}

	//上传文件
	public function UploadFile()
	{

	}

	//删除上传的图片
	public function DeleteImage()
	{

	}

	//删除上传到图标
	public function DeleteIcon()
	{

	}

	//删除上传的文件
	public function DeleteFile()
	{

	}
}