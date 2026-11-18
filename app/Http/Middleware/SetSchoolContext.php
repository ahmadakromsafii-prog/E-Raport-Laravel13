<?php
namespace App\Http\Middleware;
use App\Support\SchoolContext;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class SetSchoolContext {
 public function handle(Request $request, Closure $next): Response {
  SchoolContext::reset();
  $user=$request->user(); // auth user dibaca saat konteks masih kosong agar tidak terjadi query rekursif.
  if($user) SchoolContext::set($user->school_level,$user->role==='super_admin');
  return $next($request);
 }
}
