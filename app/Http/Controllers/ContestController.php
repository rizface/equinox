<?php

namespace App\Http\Controllers;

use App\Models\Contest;
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

    public function CreateQuestionPage() {
        return view('admin.dashboard.create-question');
    }

    public function CreateQuestion(Request $request, $id) {
        try {
            $paramsAndReturnValue = $this->ConstructParamsAndReturnValue($request,"input");
            
            Question::create([
                "contest_id" => $id,
                "title" => $request->title,
                "description" => $request["question-description"],
                "test_cases" => json_encode(["params" => $paramsAndReturnValue]),
            ]);

            Alert::success("Success", "Question successfully created");
        } catch (\Throwable $th) {
            Alert::error("Failed", "Failed create the question");
        } finally {
            return redirect(route('admin.createQuestionPage', ["id"=>$id]));
        }
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

    public function DetailQuestionPage($id, $questionId) {
        $question = Question::where("id", $questionId)->first();
        
        // TODO: fix using method from model
        $question->test_cases = json_decode($question->test_cases);
        $numberOfParams = 0;

        foreach ($question->toArray()["test_cases"]->params[0] as $key => $value) {
            $numberOfParams++;
        }
        $numberOfParams-=1;

        return view('admin.dashboard.detail-question', compact('id', 'question', 'numberOfParams'));
    }

    public function DeleteQuestion($id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            if(!$question) {
                throw new Error("Question not found");
            }

            if(!$question->Contest->ThisIsMyContest()) {
                throw new Error("Can't delete other admin's questions");
            }

            $question->delete();

            Alert::success("Success", "Success delete $question->title question");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
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

    public function UpdateQuestionPage($id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            
            if(!$question) {
                throw new Error("Question not found");
            }

            if(!$question->Contest->ThisIsMyContest()) {
                throw new Error("Can't update other admin's questions");
            };

            $question->DecodeParams();

            return view("admin.dashboard.update-question-page", compact('question'));
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
            return redirect()->back();
        }
    }

    public function UpdateQuestion(Request $request, $id, $questionId) {
        try {
            $question = Question::where("id", $questionId)->first();
            
            if(!$question) {
                throw new Error("Question not found");
            }

            if(!$question->Contest->ThisIsMyContest()) {
                throw new Error("Can't update other admin's questions");
            };

            $question->title = $request->title;
            $question->description = $request->description;
            $question->test_cases = json_encode([
                "params" => $this->ConstructParamsAndReturnValue($request, "param")
            ]);

            $question->Validate();
            $question->save();

            Alert::success("Success", "Successfully update question");
        } catch (\Throwable $th) {
            Alert::error("Failed", $th->getMessage());
        } finally {
            return redirect()->back();
        }
    }
}
