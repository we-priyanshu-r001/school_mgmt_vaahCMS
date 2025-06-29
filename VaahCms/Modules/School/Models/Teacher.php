<?php namespace VaahCms\Modules\School\Models;

// use App\Mail\BatchAssignmentMail;
use VaahCms\Modules\School\Mails\BatchAssignmentMail;
use DateTimeInterface;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Faker\Factory;
use WebReinvent\VaahCms\Models\VaahModel;
use WebReinvent\VaahCms\Traits\CrudWithUuidObservantTrait;
use WebReinvent\VaahCms\Models\User;
use WebReinvent\VaahCms\Libraries\VaahSeeder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\DB;
use VaahCms\Modules\School\Mails\SuperAdminRecordDeletedMail;
// use VaahCms\Modules\School\Models\Teacher;
use WebReinvent\VaahCms\Libraries\VaahMail;
use WebReinvent\VaahCms\Models\Taxonomy;
use VaahCms\Modules\School\Traits\DeleteMailTrait;



class Teacher extends VaahModel
{

    use SoftDeletes;
    use CrudWithUuidObservantTrait;
    use DeleteMailTrait;

    //-------------------------------------------------
    protected $table = 'sc_teachers';
    //-------------------------------------------------
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    //-------------------------------------------------
    protected $fillable = [
        'uuid',
        'email',
        'contact',
        'vh_taxonomy_gender_id',
        'vh_taxonomy_subject_id',
        'name',
        'slug',
        'is_active',
        'created_by',
        'updated_by',
        'deleted_by',
    ];
    //-------------------------------------------------
    protected $fill_except = [

    ];

    //-------------------------------------------------
    protected $appends = [
        'batch_count'
    ];

    //-------------------------------------------------
    protected function serializeDate(DateTimeInterface $date)
    {
        $date_time_format = config('settings.global.datetime_format');
        return $date->format($date_time_format);
    }

    //-------------------------------------------------
    // Relation Methods
    public function batches(){
        return $this->belongsToMany(Batch::class, 'sc_batch_sc_teacher', 'sc_teacher_id', 'sc_batch_id');
    }

    //-------------------------------------------------
    // Custom Accessors
    public function batchCount(): Attribute
    {
        // If the 'batches' relation is already loaded, this prevents an extra query
        if ($this->relationLoaded('batches')) {
            return Attribute::make(
                get: fn() => $this->batches->count(),
            );
        }

        // Otherwise, count via query
        return Attribute::make(
            get: fn() => $this->batches()->count());
    }

    // Custom Utility Methods
    public static function getGender(){
        $genders = Taxonomy::getTaxonomyByType('genders');
        return $genders->pluck('id')->toArray();
    }

    public static function getSubject(){
        $subject = Taxonomy::getTaxonomyByType('subjects');
        // dd($subject->pluck('id')->toArray());
        return $subject->pluck('id')->toArray();
    }

    public static function getAllBacthes(){
        $batches = Batch::all();
        // dd($batches->pluck('id')->toArray());
        return $batches->pluck('id')->toArray();
    }

    public static function sendBulkDeleteMail($collection){
        
        // dd($collection);
        $super_admin = User::whereHas('roles', function($role) {
        $role->where('name', 'Super Administrator');
        })->first();
        VaahMail::addInQueue(new SuperAdminRecordDeletedMail($collection, $super_admin), $super_admin->email);
    
    }


    //-------------------------------------------------
    public static function getUnFillableColumns()
    {
        return [
            'uuid',
            'created_by',
            'updated_by',
            'deleted_by',
        ];
    }
    //-------------------------------------------------
    public static function getFillableColumns()
    {
        $model = new self();
        $except = $model->fill_except;
        $fillable_columns = $model->getFillable();
        $fillable_columns = array_diff(
            $fillable_columns, $except
        );
        return $fillable_columns;
    }
    //-------------------------------------------------
    public static function getEmptyItem()
    {
        $model = new self();
        $fillable = $model->getFillable();
        $empty_item = [];
        foreach ($fillable as $column)
        {
            $empty_item[$column] = null;
        }

        $empty_item['is_active'] = 1;

        return $empty_item;
    }

    //-------------------------------------------------

    public function createdByUser()
    {
        return $this->belongsTo(User::class,
            'created_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    public function subject()
    {
        return $this->belongsTo(Taxonomy::class,
            'vh_taxonomy_subject_id', 'id'
        )->select('id', 'name');
    }
    //-------------------------------------------------
    
    public function gender()
    {
        return $this->belongsTo(Taxonomy::class,
            'vh_taxonomy_gender_id', 'id'
        );
    }
    //-------------------------------------------------
    
    public function updatedByUser()
    {
        return $this->belongsTo(User::class,
            'updated_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    //-------------------------------------------------
    public function deletedByUser()
    {
        return $this->belongsTo(User::class,
            'deleted_by', 'id'
        )->select('id', 'uuid', 'first_name', 'last_name', 'email');
    }

    //-------------------------------------------------
    public function getTableColumns()
    {
        return $this->getConnection()->getSchemaBuilder()
            ->getColumnListing($this->getTable());
    }

    //-------------------------------------------------
    public function scopeExclude($query, $columns)
    {
        return $query->select(array_diff($this->getTableColumns(), $columns));
    }

    //-------------------------------------------------
    public function scopeBetweenDates($query, $from, $to)
    {

        if ($from) {
            $from = \Carbon::parse($from)
                ->startOfDay()
                ->toDateTimeString();
        }

        if ($to) {
            $to = \Carbon::parse($to)
                ->endOfDay()
                ->toDateTimeString();
        }

        $query->whereBetween('updated_at', [$from, $to]);
    }

    //-------------------------------------------------
    public static function createItem($request)
    {

        $inputs = $request->all();

        $validation = self::validation($inputs);
        if (!$validation['success']) {
            return $validation;
        }


        // check if name exist
        $item = self::where('name', $inputs['name'])->withTrashed()->first();

        if ($item) {
            $error_message = "This name is already exist".($item->deleted_at?' in trash.':'.');
            $response['success'] = false;
            $response['errors'][] = $error_message;
            return $response;
        }

        // check if slug exist
        $item = self::where('slug', $inputs['slug'])->withTrashed()->first();

        if ($item) {
            $error_message = "This slug is already exist".($item->deleted_at?' in trash.':'.');
            $response['success'] = false;
            $response['errors'][] = $error_message;
            return $response;
        }

        // Many to Many IMPL Blocks
        $batch_ids = $inputs['batches'];
        // unset($inputs['batches']);

        $item = new self();
        $item->fill($inputs);
        // dd($inputs);
        
        $item->save();
        $item->batches()->attach($batch_ids);
        // Many to Many IMPL Block

        // Temporariliy Disabled
        // Send Mail to teacher 
        $item->load('batches');

        if ($item->email) {
            VaahMail::send(new BatchAssignmentMail($item), $item->email);
        }

        $response = self::getItem($item->id);
        $response['messages'][] = trans("vaahcms-general.saved_successfully");
        return $response;

    }

    //-------------------------------------------------
    public function scopeGetSorted($query, $filter)
    {

        if(!isset($filter['sort']))
        {
            return $query->orderBy('id', 'desc');
        }

        $sort = $filter['sort'];


        $direction = Str::contains($sort, ':');

        if(!$direction)
        {
            return $query->orderBy($sort, 'asc');
        }

        $sort = explode(':', $sort);

        return $query->orderBy($sort[0], $sort[1]);
    }
    //-------------------------------------------------
    public function scopeIsActiveFilter($query, $filter)
    {

        if(!isset($filter['is_active'])
            || is_null($filter['is_active'])
            || $filter['is_active'] === 'null'
        )
        {
            return $query;
        }
        $is_active = $filter['is_active'];

        if($is_active === 'true' || $is_active === true)
        {
            return $query->where('is_active', 1);
        } else{
            return $query->where(function ($q){
                $q->whereNull('is_active')
                    ->orWhere('is_active', 0);
            });
        }

    }
    //-------------------------------------------------
    public function scopeTrashedFilter($query, $filter)
    {

        if(!isset($filter['trashed']))
        {
            return $query;
        }
        $trashed = $filter['trashed'];

        if($trashed === 'include')
        {
            return $query->withTrashed();
        } else if($trashed === 'only'){
            return $query->onlyTrashed();
        }

    }
    //-------------------------------------------------
    public function scopeSubjectFilter($query, $filter)
    {
        // dd($query);

        if(!isset($filter['subject']))
        {
            return $query;
        }
        $subject = $filter['subject'];

        return $query->whereIn('vh_taxonomy_subject_id', $subject);

    }
    //-------------------------------------------------
    public function scopeBatchFilter($query, $filter)
    {
        // dd($query);

        if(!isset($filter['batches']))
        {
            return $query;
        }
        $batch = $filter['batches'];

        return $query->whereHas('batches', function ($q1) use ($batch) {
            $q1->whereIn('name', $batch);
        });

    }
    //-------------------------------------------------
    public function scopeBatchClickFilter($query, $filter)
    {
        if(!isset($filter['batch_uuid']))
        {
            return $query;
        }
        $id = $filter['batch_uuid'];

        return $query->whereHas('batches', function ($q1) use ($id) {
            $q1->where('sc_batches.uuid', $id);
        });
    }
    //-------------------------------------------------
    public function scopeBatchCountFilter($query, $filter)
    {
        // dd($query);

        if(!isset($filter['batch_count_min']) && !isset($filter['batch_count_max']))
        {
            return $query;
        }
        $max = $filter['batch_count_max'];
        $min = $filter['batch_count_min'];

       return $query->withCount('batches')
          ->having('batches_count', '>=', $min)
          ->having('batches_count', '<=', $max);

    }
    //-------------------------------------------------
    public function scopeGenderFilter($query, $filter)
    {
        // dd($query);

        if(!isset($filter['gender']))
        {
            return $query;
        }
        $gender = $filter['gender'];

        return $query->whereIn('vh_taxonomy_gender_id', $gender);

    }
    //-------------------------------------------------
    public function scopeSearchFilter($query, $filter)
    {

        if(!isset($filter['q']))
        {
            return $query;
        }
        $search_array = explode(' ',$filter['q']);
        // dd($search_array);
        foreach ($search_array as $search_item){
            $query->where(function ($q1) use ($search_item) {
                $q1->where('name', 'LIKE', '%' . $search_item . '%')
                    ->orWhere('slug', 'LIKE', '%' . $search_item . '%')
                    ->orWhere('id', 'LIKE', $search_item . '%')
                    ->orWhere('contact', 'LIKE', $search_item . '%')
                    ->orWhere('email', 'LIKE', $search_item . '%')

                    ->orWhereHas('batches', function ($q2) use ($search_item) {
                        $q2->where('name', 'LIKE', '%' . $search_item . '%');
                    });
            });
        }

    }
    //-------------------------------------------------
    public static function getList($request)
    {
        
        $list = self::getSorted($request->filter)->with('subject')->with('gender'); 
        $list->isActiveFilter($request->filter);
        $list->trashedFilter($request->filter);
        $list->searchFilter($request->filter);
        $list->subjectFilter($request->filter);
        $list->genderFilter($request->filter);
        $list->batchFilter($request->filter);
        $list->batchCountFilter($request->filter);
        $list->batchClickFilter($request->filter);

        $rows = config('vaahcms.per_page');
        
        if($request->has('rows'))
        {
            $rows = $request->rows;
        }
        
        $list = $list->paginate($rows);

        $response['success'] = true;
        $response['data'] = $list;

        return $response;


    }

    //-------------------------------------------------
    public static function updateList($request)
    {

        $inputs = $request->all();

        $rules = array(
            'type' => 'required',
        );

        $messages = array(
            'type.required' => trans("vaahcms-general.action_type_is_required"),
        );


        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {

            $errors = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        if(isset($inputs['items']))
        {
            $items_id = collect($inputs['items'])
                ->pluck('id')
                ->toArray();
        }

        $items = self::whereIn('id', $items_id);

        switch ($inputs['type']) {
            case 'deactivate':
                $items->withTrashed()->where(['is_active' => 1])
                    ->update(['is_active' => null]);
                break;
            case 'activate':
                $items->withTrashed()->where(function ($q){
                    $q->where('is_active', 0)->orWhereNull('is_active');
                })->update(['is_active' => 1]);
                break;
            case 'trash':
                $records = self::whereIn('id', $items_id)
                    ->get();
                $records->each->delete();
                self::sendDeleteMail($records);
                break;
            case 'restore':
                self::whereIn('id', $items_id)->onlyTrashed()
                    ->get()->each->restore();
                break;
        }

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = trans("vaahcms-general.action_successful");

        return $response;
    }

    //-------------------------------------------------
    public static function deleteList($request): array
    {
        $inputs = $request->all();

        $rules = array(
            'type' => 'required',
            'items' => 'required',
        );

        $messages = array(
            'type.required' => trans("vaahcms-general.action_type_is_required"),
            'items.required' => trans("vaahcms-general.select_items"),
        );

        $validator = \Validator::make($inputs, $rules, $messages);
        if ($validator->fails()) {

            $errors = errorsToArray($validator->errors());
            $response['success'] = false;
            $response['errors'] = $errors;
            return $response;
        }

        $items_id = collect($inputs['items'])->pluck('id')->toArray();

        // Method 1 to delete all pivot table entries there might be better ways but I'm unaware about them at the moment
        DB::table('sc_batch_sc_teacher')->whereIn('sc_teacher_id', $items_id)->delete();

        self::whereIn('id', $items_id)->forceDelete();

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = trans("vaahcms-general.action_successful");
        return $response;
    }
    //-------------------------------------------------
     public static function listAction($request, $type): array
    {

        $list = self::query();

        if($request->has('filter')){
            $list->getSorted($request->filter);
            $list->isActiveFilter($request->filter);
            $list->trashedFilter($request->filter);
            $list->searchFilter($request->filter);
        }

        switch ($type) {
            case 'activate-all':
                $list->withTrashed()->where(function ($q){
                    $q->where('is_active', 0)->orWhereNull('is_active');
                })->update(['is_active' => 1]);
                break;
            case 'deactivate-all':
                $list->withTrashed()->where(['is_active' => 1])
                    ->update(['is_active' => null]);
                break;
            case 'trash-all':
                $records = $list->get();
                $records->each->delete();
                self::sendDeleteMail($records);
                break;
            case 'restore-all':
                $list->onlyTrashed()->get()
                    ->each->restore();
                break;
            case 'delete-all':

                // Directly deleting all rows and reseting auto increment without any teacher any data in the pivot table is irrelevent
                DB::table('sc_batch_sc_teacher')->truncate();

                $list->forceDelete();
                break;
            case 'create-100-records':
            case 'create-1000-records':
            case 'create-5000-records':
            case 'create-10000-records':

            if(!config('school.is_dev')){
                $response['success'] = false;
                $response['errors'][] = 'User is not in the development environment.';

                return $response;
            }

            preg_match('/-(.*?)-/', $type, $matches);

            if(count($matches) !== 2){
                break;
            }

            self::seedSampleItems($matches[1]);
            break;
        }

        $response['success'] = true;
        $response['data'] = true;
        $response['messages'][] = trans("vaahcms-general.action_successful");

        return $response;
    }
    //-------------------------------------------------
    public static function getItem($id)
    {

        $item = self::where('id', $id)
            ->with(['createdByUser', 'updatedByUser', 'deletedByUser', 'subject', 'gender'])
            ->with('batches')
            ->withTrashed()
            ->first();
    

        if(!$item)
        {
            $response['success'] = false;
            $response['errors'][] = 'Record not found with ID: '.$id;
            return $response;
        }
        $response['success'] = true;
        $response['data'] = $item;

        return $response;

    }
    //-------------------------------------------------
    public static function updateItem($request, $id)
    {
        $inputs = $request->all();

        $validation = self::validation($inputs);
        if (!$validation['success']) {
            return $validation;
        }

        // check if name exist
        $item = self::where('id', '!=', $id)
            ->withTrashed()
            ->where('name', $inputs['name'])->first();

         if ($item) {
             $error_message = "This name is already exist".($item->deleted_at?' in trash.':'.');
             $response['success'] = false;
             $response['errors'][] = $error_message;
             return $response;
         }

         // check if slug exist
         $item = self::where('id', '!=', $id)
             ->withTrashed()
             ->where('slug', $inputs['slug'])->first();

         if ($item) {
             $error_message = "This slug is already exist".($item->deleted_at?' in trash.':'.');
             $response['success'] = false;
             $response['errors'][] = $error_message;
             return $response;
         }

         $item = self::where('id', $id)->withTrashed()->first();
         $item->fill($inputs);
         
        // Fetch the teacher record with batches
        $teacher = self::where('id', $id)->with('batches')->first();
        
        // Many to Many IMPL Blocks
        $batch_ids = $inputs['batches'];
        
        $item->batches()->sync($batch_ids);
        $item->save();
        // Many to Many IMPL Block
        

        // Capture original batch IDs before sync
        $originalBatchIds = $teacher->batches->pluck('id')->toArray();

        sort($originalBatchIds);
        sort($batch_ids);


        // Compare old vs new batches
        if ($originalBatchIds != $batch_ids) {
            // Send email notification
            // $teacher->load('batches');
            $item->load('batches');

            if ($item->email) {
                VaahMail::send(new BatchAssignmentMail($item), $item->email);
            }
        }


        $response = self::getItem($item->id);
        $response['messages'][] = trans("vaahcms-general.saved_successfully");
        return $response;

    }
    //-------------------------------------------------
    public static function deleteItem($request, $id): array
    {
        $item = self::where('id', $id)->withTrashed()->first();
        if (!$item) {
            $response['success'] = false;
            $response['errors'][] = trans("vaahcms-general.record_does_not_exist");
            return $response;
        }
        $item->batches()->detach();
        $item->forceDelete();

        $response['success'] = true;
        $response['data'] = [];
        $response['messages'][] = trans("vaahcms-general.record_has_been_deleted");

        return $response;
    }
    //-------------------------------------------------
    public static function itemAction($request, $id, $type): array
    {
        switch($type)
        {
            case 'activate':
                self::where('id', $id)
                    ->withTrashed()
                    ->update(['is_active' => 1]);
                break;
            case 'deactivate':
                self::where('id', $id)
                    ->withTrashed()
                    ->update(['is_active' => null]);
                break;
            case 'trash':
                $item = self::find($id);
                $item->delete();
                self::sendDeleteMail($item);
                break;
            case 'restore':
                self::where('id', $id)
                    ->onlyTrashed()
                    ->first()->restore();
                break;
        }

        return self::getItem($id);
    }
    //-------------------------------------------------

    public static function validation($inputs)
    {

        $rules = array(
            'name' => 'required|max:150',
            // 'slug' => 'required|max:150',
            'email' => 'required|max:150',
            'contact' => 'required|max:10|min:10',

        );

        $validator = \Validator::make($inputs, $rules);
        if ($validator->fails()) {
            $messages = $validator->errors();
            $response['success'] = false;
            $response['errors'] = $messages->all();
            return $response;
        }

        $response['success'] = true;
        return $response;

    }

    //-------------------------------------------------
    public static function getActiveItems()
    {
        $item = self::where('is_active', 1)
            ->withTrashed()
            ->first();
        return $item;
    }

    //-------------------------------------------------
    //-------------------------------------------------
    public static function seedSampleItems($records=100)
    {

        $i = 0;

        while($i < $records)
        {
            $inputs = self::fillItem(false);

            $item =  new self();
            $item->fill($inputs);

            // get all batch IDs
            $batch_ids = $inputs['batches'];
            $item->save();

            // Attach After saving the teacher item
            $item->batches()->attach($batch_ids);

            $i++;

        }

    }


    //-------------------------------------------------
    public static function fillItem($is_response_return = true)
    {
        $request = new Request([
            'model_namespace' => self::class,
            'except' => self::getUnFillableColumns()
        ]);
        $fillable = VaahSeeder::fill($request);
        if(!$fillable['success']){
            return $fillable;
        }
        $inputs = $fillable['data']['fill'];

        $faker = Factory::create();

        /*
         * You can override the filled variables below this line.
         * You should also return relationship from here
         */

        $inputs['is_active'] = 1;
        $inputs['vh_taxonomy_gender_id'] = $faker->randomElement(self::getGender());
        $inputs['vh_taxonomy_subject_id'] = $faker->randomElement(self::getSubject());
        $batches = self::getAllBacthes();
        $max_batches = $max_batches = min(count($batches), 10);
        $inputs['batches'] = $faker->randomElements($batches, rand(0, $max_batches));

        if(!$is_response_return){
            return $inputs;
        }


        $response['success'] = true;
        $response['data']['fill'] = $inputs;
        return $response;
    }

    //-------------------------------------------------
    //-------------------------------------------------
    //-------------------------------------------------


}
