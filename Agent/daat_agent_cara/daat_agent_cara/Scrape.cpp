//
//  Scrape.cpp
//  
//
//  Created by Shlomo Hassid on 06/05/2017.
//  Copyright © 2017 Shlomo Hassid. All rights reserved.
//

#include "Scrape.h"
#include <iostream>

Scrape::Scrape(Awesomium::WebCore* _webcore, Awesomium::WebView* _view, Awesomium::WebString& urlStr)
{
	this->webcore = _webcore;
	this->view = _view;
	this->url = Awesomium::WebURL(urlStr);
}
int Scrape::LoadToView(const Awesomium::WebURL& url)
{
	this->view->LoadURL(url);
	while (view->IsLoading())
		this->webcore->Update();
	Sleep(300);
	this->webcore->Update();
	return 0;
}
Awesomium::BitmapSurface* Scrape::getScreen()
{
	return (Awesomium::BitmapSurface*)this->view->surface();
}
int Scrape::saveScreenTo(const Awesomium::WebString& path, Awesomium::BitmapSurface* surface)
{
	// Make sure our surface is not NULL-- it may be NULL if the WebView process has crashed.
	if (surface != 0) {
		// Save our BitmapSurface to a JPEG image in the current working directory.
		surface->SaveToJPEG(path);
		return 0;
	}
	return 1;
}
int Scrape::getAndSaveScreen(const Awesomium::WebString& path) {
	return this->saveScreenTo(path, this->getScreen());
}
int Scrape::deliverPayload(std::string& payload, Awesomium::WebString* result) {
	Awesomium::WebString thepayload = Awesomium::WSLit(payload.c_str());
	Awesomium::JSValue returned = this->view->ExecuteJavascriptWithResult(thepayload, Awesomium::WSLit(""));
	if (returned.IsString()) {
		result->Append(returned.ToString());
		return 0;
	}
	return 1;
}
Scrape::~Scrape()
{
	
}