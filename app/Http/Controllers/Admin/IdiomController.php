<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\IdiomFrom;
use App\Models\Idiom;
use App\Models\Level;
use function foo\func;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Validation\Validator;

class IdiomController extends TemplateController
{
    protected $model;
    public $config=[
        "title"=>'成语管理',
        'index'=>'idiom.index',//首页
        'create'=>'idiom.create',//创建
        'store'=>'idiom.store',//创建保存
       // 'show'=>'idiom.show',//查看
        'edit'=>'idiom.edit',//编辑
        'update'=>'idiom.update',//编辑保存
        'delete'=>'idiom.destroy',//删除
    ];
    public function __construct()
    {
        $this->model= new Idiom();

    }


    public function update(IdiomFrom $request, $id)
    {
        $model=$this->model->find($id);
        $model->fill($request->all());
        if ($model->save()){
            return response()->json(['code' => 200, 'msg' => '修改成功']);
        }else{
            return response()->json(['code' => 400, 'msg' => '修改失败']);
        }
    }
    public function edit($id)
    {
        $level=Level::get();
        $idiom=Idiom::find($id);
       // $data['config'] = $this->config;//获取配置
        return view('admin.idiom.edit',compact('level','idiom'));

    }
    public function create(){
        $level=Level::get();
        return view('admin.idiom.create',compact('level'));
    }

    public function index(Request $request)
    {
        if ($request->ajax()){
            return response()->json($this->getData($request));
        }
        $data['title'] = $this->getTitle();// 标题
        $data['config'] = $this->config;//获取配置
        return view('admin.idiom.index', ['data'=>$data]);
    }

    public function store(IdiomFrom $request){
        $model=$this->model;
        $model->fill($request->all());
        if ($model->save()){
            return response()->json(['code' => 200, 'msg' => '添加成功']);
        }else{
            return response()->json(['code' => 400, 'msg' => '添加失败']);
        }
    }
    public function getData($request){
        $model= $this->model;
        $page=$request->page??'1';
        $limit=$request->limit??'10';
        $query=$model->with('level');
        $count=$query->count();
        $paginate=$query->orderByDesc('created_at')->paginate($limit);

        $paginate->transform(function ($item,$key){
            $item->level_id=$item->level->level;
            return $item;
              });
       $data=$paginate->toArray();

        return  $data=['code'=>0,'msg'=>'','count'=>$count,'data'=>$data['data']];
    }


    public function getTitle()
    {
        return[[
            ['type'=>'checkbox'],
            ['field'=>'id','title'=>'ID','sort'=>'true'],
            ['field'=>'name','title'=>'名称'],
            ['field'=>'spell','title'=>'拼写'],
            ['field'=>'explain','title'=>'解释'],
            ['field'=>'derivation','title'=>'出处'],
            ['field'=>'sample','title'=>'用例'],
            ['field'=>'pinyin','title'=>'拼音'],
            ['field'=>'first_leter','title'=>'首字'],
            ['field'=>'last_word','title'=>'尾字'],
            ['field'=>'antonym','title'=>'近义词'],
            ['field'=>'thesaurus','title'=>'同义词'],
            ['field'=>'level_id','title'=>'等级'],
            ['field'=>'story','title'=>'故事'],
            ['field'=>'right','title'=>'数据操作','align'=>'center','toolbar'=>'#barDemo','width'=>300]
        ]];
    }


}
