<?php
class InfoController extends BaseController {
	public function getText($id,$tag=null)
	{
	Log::info("call : ".__METHOD__);
		if(!isset($id)) {
			return Redirect::error('404');
		}
		if(!isset($tag)) {
			$tag="latest";
		}
		Log::info("getTitle called. id:$id,tag:$tag");
		$info=Info::where('rid','=',$id)
			->where('tag','=',$tag)
			->get();
		Log::info("get: ".print_r($info,true));
		return Response::json($info->toArray());
	}
	public function updateText($id,$tag=null)
	{
	Log::info("call : ".__METHOD__);
		if(!isset($tag)) {
			$tag="latest";
		}
		$input=Input::all();
		if(isset($input)) {
			if(isset($input['title'])) {
				$title=$input['title'];
				Log::info("input title: $title");
			}
			if(isset($input['descript'])) {
				$desc=$input['descript'];
				Log::info("input descript: $desc");
			}
		}

		//Log::info("input ($id,$tag): ".print_r($input,true));
		Log::info("rid:$id, tag:$tag ");
		$info=Info::where('rid','=',$id)
			->where('tag','=',$tag)
			->first();
		Log::info("before info: ".print_r($info,true));
		if(isset($info)) {
			if(isset($title)) {
				$info->title=$title;
				Log::info("update title: $title");
			}
			if(isset($desc)) {
				$info->descript=$desc;
				Log::info("update descript: $desc");
			}
			$r=$info->save();
			if($r) {
			Log::info("update return: ".print_r($r,true));
			}
		} else {
			$info=new Info();
			$info->rid=$id;
			$info->tag=$tag;
			if(isset($title)) {
				$info->title=$title;
			}else{
				$info->title="";
			}
			if(isset($desc)) {
				$info->descript=$desc;
			}else{
				$info->descript="";
			}
			$r=$info->save();
			Log::info("insert return: ".print_r($r,true));
		}

		return Response::json(array("OK"));
	}
}
