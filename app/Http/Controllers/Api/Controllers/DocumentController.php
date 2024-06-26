<?php

namespace App\Http\Controllers\Api\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDocumentRequest;
use App\Http\Resources\DocumentConfiguration;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\SingleDocumentResource;
use App\Models\Document;
use App\Models\Product;
use Illuminate\Http\Request;

class DocumentController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/v1/documents",
     *     tags={"Documents"},
     *     summary="list of documents",
     *       description="Returns a list of items",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="document_name",
     *                     type="string",
     *                     example="Item document_name"
     *                 ),
     *                 @OA\Property(
     *                     property="created_at",
     *                     type="string",
     *                     example="2022-01-12T13:11:59.000000Z"
     *                 ),
     *                 @OA\Property(
     *                     property="field_count",
     *                     type="integer",
     *                     example="3"
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function index(Request $request)
    {
        $documents = Document::all();
        $documentsData = DocumentResource::collection($documents);

        return $documentsData;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/document/{id}",
     *     tags={"Documents"},
     *     summary="document",
     *       description="Returns a document",
     *      @OA\Parameter(
     *          name="id",
     *          description="Document id",
     *          example=1,
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *          type="integer"
     *          )
     *      ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *                 type="object",
     *                 @OA\Property(
     *                     property="id",
     *                     type="integer",
     *                     example=1
     *                 ),
     *                 @OA\Property(
     *                     property="name",
     *                     type="string",
     *                     example="Item Name"
     *                 ),
     *                 @OA\Property(
     *                     property="content",
     *                     type="string",
     *                     example="Item Description"
     *                 ),
     *                 @OA\Property(
     *                     property="price",
     *                     type="integer",
     *                     example="100"
     *                 )
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Not found"
     *     )
     * )
     */
    public function document(Request $request)
    {

        $document = Document::findOrFail($request->id);

        $documentConfiguration = $document->configurations;

        return [
            "documentName" => $document->document_name,
            "fields" => DocumentConfiguration::collection($documentConfiguration)
        ];
    }

    /**
     * @OA\Post(
     *     path="/api/v1/document/create",
     *     tags={"Documents"},
     *     summary="new document",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"document_name"},
     *             @OA\Property(property="document_name", type="string", example="Apple"),
     *             @OA\Property(property="content", type="string",  example="this id good fruit"),
     *             @OA\Property(property="price", type="integer",  example="3000")
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful create document",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Invalid credentials",
     *     )
     * )
     */
    public function create(CreateDocumentRequest $request)
    {
        $document = Document::create(["document_name" => $request->document_name]);

        $form_values = $request->form_values;

        foreach ($form_values as $value) {
            $document->documentConfigurations()->create([
                'field_seq' => $value["field_seq"],
                'is_mandatory' => $value["is_mandatory"],
                'field_type' => $value["field_type"],
                'field_name' => $value["field_name"],
                'select_values' => json_encode($value["select_values"]),
            ]);
        }

        return $document;
    }
}
