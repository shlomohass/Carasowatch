//
//  Scrape.hpp
//  
//
//  Created by Shlomo Hassid on 06/05/2017.
//  Copyright © 2017 Shlomo Hassid. All rights reserved.
//

#ifndef Scrape_hpp
	#define Scrape_hpp

#include <iostream>

//Headless browser includes:
#include <Awesomium/WebCore.h>
#include <Awesomium/BitmapSurface.h>
#include <Awesomium/STLHelpers.h>

class Scrape
{
	Awesomium::WebCore* webcore;
	Awesomium::WebView* view;
	
	
	public:

		Awesomium::WebURL   url;

		Scrape(Awesomium::WebCore* _webcore, Awesomium::WebView* _view, Awesomium::WebString& urlStr);
		int LoadToView(const Awesomium::WebURL& url);
		Awesomium::BitmapSurface* getScreen();
		int saveScreenTo(const Awesomium::WebString& path, Awesomium::BitmapSurface* surface);
		int getAndSaveScreen(const Awesomium::WebString& path);
		int deliverPayload(std::string& payload, Awesomium::WebString* result);
		virtual ~Scrape();
};

#endif /* Scrape_hpp */

