<?php

namespace App\Http\Controllers;

use App\Models\Coder;
use App\Models\Contest;
use App\Models\Participant;
use App\Models\Question;
use App\Traits\UtilsTrait;
use Error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class ContestController extends Controller
{
    use UtilsTrait;

    public function Dashboard() {
        $allCourses = Contest::orderBy("updated_at", "desc")
        ->get();
        $myCourses = Contest::where("admin_id", Auth::guard("admin")->user()->id)
        ->orderBy("updated_at", "desc")
        ->get();
        
        return view('admin.dashboard.contest', compact('allCourses','myCourses'));
    }

    public function CreateContest(Request $request) {
        try {
            $contest = Contest::create([
                "admin_id" => Auth::guard("admin")->user()->id,
                "title" => $request->title,
            ]); 

            Alert::success("Success", "Successfully create $contest->title contest");

            return redirect(route("admin.createQuestionPage", [
                "id" => $contest->id,
            ]));
        } catch (\Throwable $th) {
            Alert::error("Failed", "Failed create new contest");

            return redirect()->back();
        }
    }

    public function CreateContestPage() {
        return view('admin.dashboard.create-contest');
    }

    public function DetailContestPage($id) {
        $contest = Contest::where("id", $id)->first();
        return view('admin.dashboard.detail-contest', compact('contest'));
    }

    public function DeleteCourse($id) {
        try {
            $course = Contest::where("id", $id)->first();
            if(!$course) {
                throw new Error("Contest not found");
            }

            if(!$course->ThisIsMyContest()) {
                throw new Error("Can't delete other admin's courses");
            }

            $course->delete();

            Alert::success("Success", "Success delete $course->title course");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function UpdateCoursePage($id) {
        try {
            $course = Contest::where("id", $id)->first();
            if(!$course) {
                throw new Error("Course not found");
            }

            if(!$course->ThisIsMyContest()) {
                throw new Error("Can't update other admin's courses");
            }

            return view("admin.dashboard.update-contest", compact('course'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        }
    }

    public function UpdateCourse(Request $request, $id) {
        try {
            $course = Contest::where("id", $id)->first();
            if(!$course) {
                throw new Error("Course not found");
            }

            if(!$course->ThisIsMyContest()) {
                throw new Error("Can't update other admin's courses");
            }

            $course->title = $request->title;

            $error = $course->Validate();
            if($error) {
                throw new Error($error);
            }

            $course->save();

            Alert::success("Success", "Successfully update course");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }

    public function AvailableCoursesForCoders() {
        $allCourses = Contest::all();
        return view("coder.dashboard.courses", compact("allCourses"));
    }

    public function DetailCoursePageForCoder($id) {
        try {
            $course = Contest::where("id", $id)->first();
            if (!$course) {
                throw new Error("Course not found");
            }

            return view("coder.dashboard.detail-course", compact('course'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        }
    }

    public function CoderJoinCourse($courseId, $coderId) {
        try {
            $course = Contest::where("id", $courseId)->first();
            if (!$course) {
                throw new Error("Course not found");
            }

            $coder = Coder::where("id", $coderId)->first();
            if(!$coder) {
                throw new Error("Coder not found");
            }

            if ($coder->id != Auth::guard("coder")->user()->id) {
                throw new Error("Something wrong with this coder");
            }
            
            if ($coder->AlreadyJoinThisCourse($courseId)) {
                throw new Error("Your are already join this course");
            }
            
            Participant::create([
                "coder_id" => $coderId,
                "contest_id" => $courseId
            ]);

            Alert::success("Success", "Success join course $course->title");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
