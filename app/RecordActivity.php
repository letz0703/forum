<?php
/**
 * Created by letz.
 * User: letz
 * Date: 2018. 7. 30.
 * Time: AM 1:27
 */

namespace App;


trait RecordActivity {

    protected static function bootRecordActivity()
    {
        if ( auth()->guest() ) return; // Fix Mass Assign Error
        
        foreach (static::getActivityToRecord() as $event)
            static::$event(function ($model) use ($event)
            {
                $model->recordActivity($event);
            });

        static::deleting(function($model){
            $model->activity()->delete();
        });
    }

    protected static function getActivityToRecord()
    {
        return ['created'];
    }

    public function getActivityType($event)
    {
        $type = strtolower(class_basename($this));

        return "{$event}_{$type}";
    }

    protected function recordActivity($event)
    {

//        Activity::create([
        $this->activity()->create([
            'user_id' => auth()->id(),
            'type'    => $this->getActivityType($event),
//            'subject_id'   => $this->id,
//            'subject_type' => get_class($this)
        ]);
    }

    public function activity()
    {
        return $this->morphMany('App\Activity', 'subject');
    }
}