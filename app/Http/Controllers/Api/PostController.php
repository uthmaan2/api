<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Http\Controllers\Controller;
use App\Http\Requests\createApiRequest;
use App\Http\Requests\editPostRequest;
use Exception;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request){
        try {
            $query= Post::query();
            $perpage=3;
            $page= $request->input('page',1);
            $search = $request->input('search');
            if($search){
                $query->whereRaw("titre LIKE '%". $search. "%'");
            }
            $total = $query->count();
            $result = $query->offset(($page-1)*$perpage)->limit($perpage)->get();

            return response()->json([
                'status_code'=>200,
                'status_message'=> 'les posts recupérés',
                'current_page'=>$page,
                'last_page'=>ceil($total/$perpage),
                'items'=>$result
               ]);
        } catch (Exception $e) {
            return response()->json($e);
        }

        return 'liste des articles';
    }
    public function store(createApiRequest $request){
        try {
            
            $user_id = auth()->user()->id; // Récupère l'ID de l'utilisateur authentifié
            $postData = $request->all();
            $postData['user_id'] = $user_id; // Ajoute l'ID de l'utilisateur au tableau de données
            //dd($postData);
            $post = Post::create($postData);          
          
            return response()->json([
             'status_code'=>200,
             'status_message'=> 'le post à été ajouté',
             'data'=>$post
            ]);
     
        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500); // Afficher l'erreur
        }
        
        
    }
    public function update(editPostRequest $request, Post $post){
        try {
            $post->titre= $request->titre;
            $post->description = $request->description;
            if ($post->user_id===auth()->user()->id) {
                $post->save();
            } else {
                return response()->json([
                    'status_code'=>422,
                    'status_message'=> "vous n 'etes pas l'auteur de ce post",
                    'data'=>$post
                   ]);
    
            }
            
            return response()->json([
                'status_code'=>200,
                'status_message'=> 'le post à été modifié',
                'data'=>$post
               ]);
   
    
        } catch (Exception $me) {
            return response()->json($me);
        }
    }
    public function destroy(Post $post){
        try {
            if (!$post) {
                return response()->json([
                    'status_code' => 404,
                    'status_message' => "Le post n'existe pas."
                ]);
            }
    
            if ($post->user_id === auth()->user()->id) {
                $post->delete();
                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'Le post a été supprimé',
                    'data' => $post
                ]);
            } else {
                return response()->json([
                    'status_code' => 422,
                    'status_message' => "Vous n'êtes pas l'auteur de ce post. Suppression non autorisée."
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Une erreur s\'est produite lors de la suppression du post.',
                'error' => $e->getMessage()
            ]);
        }
    }
    
}
