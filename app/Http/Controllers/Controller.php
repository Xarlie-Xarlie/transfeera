<?php

namespace App\Http\Controllers;

/**
 * @OA\Server(
 *        url="http://0.0.0.0:80",
 *        description="Ambiente de desenvolvimento do projeto localmente"
 *  ),
 *
 * @OA\PathItem(
 *       path="/app"
 *   ),
 *
 *
 * @OA\OpenApi(
 *     @OA\Info(title="Receiver API", version="1.0")
 * )
 *
 * @OA\Schema(
 *     schema="Receiver",
 *     type="object",
 *     title="Receiver",
 *     required={"name", "cpf_cnpj", "banco", "agencia", "conta", "pix_key_type", "pix_key"},
 *     @OA\Property(property="name", type="string", description="The receiver's name"),
 *     @OA\Property(property="cpf_cnpj", type="string", description="The receiver's CPF or CNPJ"),
 *     @OA\Property(property="banco", type="string", description="The receiver's bank"),
 *     @OA\Property(property="agencia", type="string", description="The receiver's agency"),
 *     @OA\Property(property="conta", type="string", description="The receiver's account"),
 *     @OA\Property(property="pix_key_type", type="string", description="The type of the PIX key"),
 *     @OA\Property(property="pix_key", type="string", description="The PIX key"),
 *     @OA\Property(property="email", type="string", description="The receiver's email")
 * )
 *
 * @OA\Get(
 *     path="/api/receiver",
 *     summary="Get all receivers",
 *     @OA\Parameter(
 *         name="status",
 *         in="query",
 *         description="Filter by status",
 *         required=false,
 *         example="validado",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="name",
 *         in="query",
 *         description="Filter by name",
 *         required=false,
 *         example="John Doe",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pix_key_type",
 *         in="query",
 *         description="Filter by Pix Key Type",
 *         required=false,
 *         example="TELEFONE",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="pix_key",
 *         in="query",
 *         description="Filter by Pix Key",
 *         required=false,
 *         example="+55 (99) 92145-1234",
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="per_page",
 *         in="query",
 *         description="Quantity of items in a page at a maximun of 50 items",
 *         required=false,
 *         example="25",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Parameter(
 *         name="page",
 *         in="query",
 *         description="The page of paginated items",
 *         required=false,
 *         example="4",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="Receivers Found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="array",
 *                  @OA\Items(
 *                      @OA\Property(property="id", type="integer", example="1"),
 *                      @OA\Property(property="name", type="string", example="john_doe"),
 *                      @OA\Property(property="cpf_cnpj", type="string", example="723.089.645-59"),
 *                      @OA\Property(property="banco", type="string", example="Brakus, Mayer and Botsford"),
 *                      @OA\Property(property="agencia", type="string", example="2828"),
 *                      @OA\Property(property="conta", type="string", example="020755703794641"),
 *                      @OA\Property(property="status", type="string", example="validado"),
 *                      @OA\Property(property="pix_key_type", type="string", example="CNPJ"),
 *                      @OA\Property(property="pix_key", type="string", example="43.107.463/3321-50"),
 *                      @OA\Property(property="email", type="string", example="pacocha.cristopher@example.com"),
 *                      @OA\Property(property="created_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *                      @OA\Property(property="updated_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *                  ),
 *              ),
 *              @OA\Property(property="path", type="string", example="http://localhost/api/receiver"),
 *              @OA\Property(property="per_page", type="integer", example="1"),
 *              @OA\Property(property="to", type="integer", example="1"),
 *              @OA\Property(property="total", type="integer", example="1")
 *          ),
 *     ),
 *     @OA\Response(
 *          response=404,
 *          description="Receivers not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="message", type="string", example="Receivers not found"
 *              )
 *          )
 *     ),
 * )
 *
 * @OA\Get(
 *     path="/api/receiver/{id}",
 *     summary="Get a single receiver by it's `id`",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the receiver",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="Receivers Found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example="1"),
 *                  @OA\Property(property="name", type="string", example="john_doe"),
 *                  @OA\Property(property="cpf_cnpj", type="string", example="723.089.645-59"),
 *                  @OA\Property(property="banco", type="string", example="Brakus, Mayer and Botsford"),
 *                  @OA\Property(property="agencia", type="string", example="2828"),
 *                  @OA\Property(property="conta", type="string", example="020755703794641"),
 *                  @OA\Property(property="status", type="string", example="validado"),
 *                  @OA\Property(property="pix_key_type", type="string", example="CNPJ"),
 *                  @OA\Property(property="pix_key", type="string", example="43.107.463/3321-50"),
 *                  @OA\Property(property="email", type="string", example="pacocha.cristopher@example.com"),
 *                  @OA\Property(property="created_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *              ),
 *          ),
 *     ),
 *     @OA\Response(
 *          response=404,
 *          description="Receiver not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="message", type="string", example="Receiver not found"
 *              )
 *          )
 *     ),
 * )
 *
 * @OA\Post(
 *     path="/api/receiver",
 *     summary="Create a new receiver",
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="john_doe"),
 *             @OA\Property(property="cpf_cnpj", type="string", example="723.089.645-59"),
 *             @OA\Property(property="banco", type="string", example="Brakus, Mayer and Botsford"),
 *             @OA\Property(property="agencia", type="string", example="2828"),
 *             @OA\Property(property="conta", type="string", example="020755703794641"),
 *             @OA\Property(property="pix_key_type", type="string", example="CNPJ"),
 *             @OA\Property(property="pix_key", type="string", example="43.107.463/3321-50"),
 *             @OA\Property(property="email", type="string", example="pacocha.cristopher@example.com"),
 *         )
 *     ),
 *     @OA\Response(
 *          response=201,
 *          description="Receiver created",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example="1"),
 *                  @OA\Property(property="name", type="string", example="john_doe"),
 *                  @OA\Property(property="cpf_cnpj", type="string", example="723.089.645-59"),
 *                  @OA\Property(property="banco", type="string", example="Brakus, Mayer and Botsford"),
 *                  @OA\Property(property="agencia", type="string", example="2828"),
 *                  @OA\Property(property="conta", type="string", example="020755703794641"),
 *                  @OA\Property(property="status", type="string", example="validado"),
 *                  @OA\Property(property="pix_key_type", type="string", example="CNPJ"),
 *                  @OA\Property(property="pix_key", type="string", example="43.107.463/3321-50"),
 *                  @OA\Property(property="email", type="string", example="pacocha.cristopher@example.com"),
 *                  @OA\Property(property="created_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *              ),
 *          ),
 *     ),
 *     @OA\Response(response="400", description="Bad request")
 * )
 *
 *  @OA\Put(
 *     path="/api/receiver/{id}",
 *     summary="Update a receiver",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the receiver",
 *         required=true,
 *         example="1",
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="name", type="string", example="john_doe"),
 *             @OA\Property(property="cpf_cnpj", type="string", example="723.089.645-59"),
 *             @OA\Property(property="banco", type="string", example="Brakus, Mayer and Botsford"),
 *             @OA\Property(property="agencia", type="string", example="2828"),
 *             @OA\Property(property="conta", type="string", example="020755703794641"),
 *             @OA\Property(property="pix_key_type", type="string", example="CNPJ"),
 *             @OA\Property(property="pix_key", type="string", example="43.107.463/3321-50"),
 *             @OA\Property(property="email", type="string", example="pacocha.cristopher@example.com"),
 *         )
 *     ),
 *     @OA\Response(
 *          response=200,
 *          description="Receivers Found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="data",
 *                  type="object",
 *                  @OA\Property(property="id", type="integer", example="1"),
 *                  @OA\Property(property="name", type="string", example="john_doe"),
 *                  @OA\Property(property="cpf_cnpj", type="string", example="723.089.645-59"),
 *                  @OA\Property(property="banco", type="string", example="Brakus, Mayer and Botsford"),
 *                  @OA\Property(property="agencia", type="string", example="2828"),
 *                  @OA\Property(property="conta", type="string", example="020755703794641"),
 *                  @OA\Property(property="status", type="string", example="validado"),
 *                  @OA\Property(property="pix_key_type", type="string", example="CNPJ"),
 *                  @OA\Property(property="pix_key", type="string", example="43.107.463/3321-50"),
 *                  @OA\Property(property="email", type="string", example="pacocha.cristopher@example.com"),
 *                  @OA\Property(property="created_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *                  @OA\Property(property="updated_at", type="string", example="2024-06-09T15:06:23.000000Z"),
 *              ),
 *          ),
 *     ),
 *     @OA\Response(
 *          response=404,
 *          description="Receiver not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="message", type="string", example="Receiver not found"
 *              )
 *          )
 *     ),
 * )
 *
 * @OA\Delete(
 *     path="/api/receiver/{id}",
 *     summary="Delete a receiver",
 *     @OA\Parameter(
 *         name="id",
 *         in="path",
 *         description="ID of the receiver",
 *         required=true,
 *         @OA\Schema(type="integer")
 *     ),
 *     @OA\Response(response="204", description="No content"),
 *     @OA\Response(
 *          response=404,
 *          description="Receiver not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="message", type="string", example="Receiver not found"
 *              )
 *          )
 *     ),
 * )
 *
 * @OA\Delete(
 *     path="/api/receivers",
 *     summary="Delete a set of receivers",
 *     @OA\Parameter(
 *         name="ids",
 *         in="query",
 *         description="IDs of the receivers you want to delete",
 *         required=true,
 *         example="{'ids': [1, 2, 3, 4]}",
 *         @OA\Schema(type="object")
 *     ),
 *     @OA\RequestBody(
 *         required=true,
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="ids",
 *                  type="object",
 *                  example="[1, 2, 3, 4]"
 *              ),
 *          ),
 *     ),
 *     @OA\Response(response="204", description="No content"),
 *     @OA\Response(
 *          response=404,
 *          description="Receivers not found",
 *          @OA\JsonContent(
 *              type="object",
 *              @OA\Property(
 *                  property="message", type="string", example="Receivers not found"
 *              )
 *          )
 *     ),
 * )
 */
abstract class Controller
{
    //
}
