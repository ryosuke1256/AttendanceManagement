<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Http\Requests\AttendanceRecordRequest;
use App\Services\AttendanceRecordService;
use App\Models\AttendanceRecord;

class AttendanceRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('history');
    }

    /**
     * Display the start page
     *
     * @return \Illuminate\Http\Response
     */
    public function start()
    {
        // TODO Refactor
        $date = new Carbon();
        $hour = $date->hour;
        $minute = $date->minute;
        $maxTime = $hour . ':' . $minute;

        return view('start', ['maxTime' => $maxTime]);
    }

    /**
     * Display the end page
     *
     * @return \Illuminate\Http\Response
     */
    public function end()
    {
        // TODO Refactor
        $date = new Carbon();
        $hour = $date->hour;
        $minute = $date->minute;
        $maxTime = $hour . ':' . $minute;

        return view('end', ['maxTime' => $maxTime]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AttendanceRecordRequest $request)
    {
        // TODO ないなら
        AttendanceRecord::create(['user_id' => Auth::id(), 'start_time' => $request->time]);
        return redirect()->route('home');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('end');
    }

    /**
     * Updates the record of the most recent start of the day in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = \Auth::user();

        AttendanceRecordService::getTodayStartedRecord($user)->update([
            'end_time' => $request->time,
        ]);
        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AttendanceRecord::where('id', $id)->delete();
        return redirect()->route('home');
    }
}
