<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
class CustomExceptionHandler extends Command
{
    public function render($request, \Throwable $e): JsonResponse
    {
        if ($e instanceof NotFoundHttpException) {
            return response()->json(['message' => 'The requested route could not be found.', 'status' => 404], 404);
        }
        return parent::render($request, $e);
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:custom-exception-handler';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
    }
}