<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Service;

class ServicesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api')->except(['index', 'show']);
    }

    /**
     * Return all existing Services.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $services = Service::all();
        return $services;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(request(), [
            'title' => ['required', 'min:3'],
            'description' => ['required', 'min:3'],
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zipcode' => 'required',
        ]);
        Service::create([
            'title' => request('title'),
            'description' => request('description'),
            'address' => request('address'),
            'city' => request('city'),
            'state' => request('state'),
            'zipcode' => request('zipcode'),
            'latitude' => request('latitude'),
            'longitude' => request('longitude')
        ]);
        return ['message' => 'Services Created'];
    }

    /**
     * Return the specified resource.
     *
     * @param  Service $service * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        return $service;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Service $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        $service->update(request(['title', 'description', 'address']));
        return ['message' => 'Service Updated'];
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Service $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        $service->delete();
        return ['message' => 'Service Deleted'];
    }
}
