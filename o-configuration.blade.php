@extends('app-docs',[
	'DocsSidebarL' => menu('DocsGeneralSidebar'),
	'DocsSidebarR' => menu('DocsSummarySidebar')
])

@section('doc-title', 'Advanced configurations')
@section('seo-title', 'Set-up the environment according to your preferences...')

@section('doc-content')

<p>TODO: Explain each key in the config file...</p>


@endsection