<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Jobs;
use App\Models\User;
use App\Models\JobsLikes;
use App\Models\JobsApplies;
use App\Providers\JobsServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JobsController extends Controller
{
    /**
     * Show the profile for a given user.
     *
     * @param  int  $id
     * @param  string  $author
     * @return \Illuminate\View\View
     */
    public function show($id, $author)
    {
        $jobsAuthor2 = User::where('username', '=', $author)->firstOrFail();
        $cat = DB::table('jobs')->where('id', $id)->first();
        $latestJobs = Jobs::where('active', 1)->where('category', $cat->category)->orderBy('created_at', 'desc')->limit('3')->get();

        $ipVue = DB::table('jobs_vues')->where('ip', request()->ip())->get();
        $likeVue = DB::table('jobs_likes')->where('id', $id)->get();
        

        if(Auth::user() != null)
        {
            $apply = JobsApplies::where('author', '=', Auth::user()->username)->firstOrFail();
        }else{
            $apply = null;
        }

        $publicite = DB::table('jobs_pub')->first();

        if($likeVue->count() == 0)
        {
            $likeVue->ip = null;
        }

        if($ipVue->count() == 0)
        {
            /*
                Insert le nombre de vue
            */
            DB::table('jobs_vues')->insert([
                'jobs_id' => $id,
                'ip' => request()->ip()
            ]);

            /*
                Met à jour le nombre de vue
            */
            DB::table('jobs')
            ->where('id', $id)->increment('vue');                       
        }  

        return view('jobs-details', [
            'jobs' => Jobs::findOrFail($id),
            'userJobs' => $jobsAuthor2,
            'latestJobs' => $latestJobs,
            'likeVue' => $likeVue,
            'ipUser' => request()->ip(),
            'publicite' => $publicite,
            'apply' => $apply
        ]);
    }

    /**
     * @param  int  $id
     * @param  string  $author
     * @return \Illuminate\View\View
     */
     public function likes($id)
     {
 
         $ipVue = DB::table('jobs_likes')->where('ip', request()->ip())->get();
 
         if(Auth::user()) {
            if($ipVue->count() == 0)
            {
                /*
                    Insert le nombre de like
                */
                DB::table('jobs_likes')->insert([
                    'jobs_id' => $id,
                    'ip' => request()->ip()
                ]);
    
                /*
                    Met à jour le nombre de like
                */
                DB::table('jobs')
                ->where('id', $id)->increment('likes');     
                
                return back()->with('success','Merci, pour votre vote.');
            }else{
                return back()->with('error','Désoler, vous avez déjà voté.');
            }
        }    
 
     }

     /**
     * @param  int  $id
     * @param  string  $author
     * @return \Illuminate\View\View
     */
    public function apply($id, $author)
    {
        $apply = DB::table('jobs_apply')->where('author', Auth::user()->username)->get();
 
        if(Auth::user()) {

           if($apply->count() == 0)
           {
               /*
                   Insert le nombre de apply
               */
               DB::table('jobs_apply')->insert([
                   'jobs_id' => $id,
                   'author' => Auth::user()->username
               ]);
   
               /*
                   Met à jour le nombre de apply
               */
               DB::table('jobs')
               ->where('id', $id)->increment('apply');     
               
               return back()->with('success','Merci, d\'avoir postulé à cette offre.');
           }else{
               return back()->with('error','Désoler, vous avez déjà postulé à cette offre.');
           }
           
       }    
    }

     /**
     * Show the profile for a given user.
     *
     * @param  string  $category
     * @return \Illuminate\View\View
     */
    public function show_cat($category)
    {

    }

}