<?php
namespace Ember\Http;
use Ember\Http\Request;
use Ember\Http\Response;
interface ActionInterface
{
    public function handle(Request $request): Response;
}
