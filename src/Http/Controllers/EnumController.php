<?php

namespace EnumTools\Http\Controllers;

use EnumTools\Http\Middleware\SetEnumLocale;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class EnumController extends Controller
{
    /**
     * @param string $enum
     * @return Response
     */
    public function __invoke(string $enum, Request $request): Response
    {
        // Apply locale middleware manually
        (new SetEnumLocale())->handle($request, fn() => null);

        $enum = Str::studly($enum); // Transform 'example-enum' to 'ExampleEnum'

        $allowed = config('enum_tools.allowed_enums', []);

        if (!in_array($enum, $allowed, true)) {
            return response()->json([
                'success'   => false,
                'message'   => 'Access denied.'
            ], Response::HTTP_FORBIDDEN);
        }

        $class = config('enum_tools.namespace') . "\\$enum";

        if (!enum_exists($class)) {
            return response()->json([
                'success'   => false,
                'message'   => "Enum [$class] not found.",
            ], Response::HTTP_NOT_FOUND,);
        }

        if (!method_exists($class, 'toArray')) {
            return response()->json([
                'success'   => false,
                'message'   => "Enum [$class] must use HasLabel trait."
            ], Response::HTTP_BAD_REQUEST);
        }

        return response()->json([
            'success'   => true,
            'data'      => $class::toArray(),
        ], Response::HTTP_OK);
    }
}
