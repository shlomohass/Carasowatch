// daat_agent_cara.cpp : Defines the entry point for the console application.
//
// 
//
// PreBuild events:
// Run batch "postcopy"-> uses robcopy

#include <fstream>
#include <iostream>
#include <string>
#include <stdio.h>
#include <time.h>
#include <sstream>
#include "Base64.h"
#include "argvparser.h"
#include "Scrape.h"

//Headless browser includes:
#include <Awesomium/WebCore.h>
#include <Awesomium/BitmapSurface.h>
#include <Awesomium/STLHelpers.h>

// Various macro definitions
#define SCRAPER_VERSION "9.11"
#define SCRAPER_AUTHOR "Shlomo Hassid"
#define WEB_WIDTH   1200
#define WEB_HEIGHT  980
#define URL_TARGET "aHR0cDovL3d3dy5jYXJ0dWJlLmNvLmlsLw==" // http://www.cartube.co.il/
#define OUT_FOLDER  "C:\\Scraperout\\"

enum ProgExitCodes {
	PEC_SUCCESS,
	PEC_ERR_OPTIONS,
	PEC_ERR_PAYLOAD_BASE,
	PEC_ERR_TARGET_BASE,
	PEC_ERR_PAYLOAD_EXEC,
	PEC_ERR_DUMP_WRITE,
};

//http://www.cartube.co.il/%D7%97%D7%93%D7%A9%D7%95%D7%AA-%D7%A8%D7%9B%D7%91
//aHR0cDovL3d3dy5jYXJ0dWJlLmNvLmlsLyVENyU5NyVENyU5MyVENyVBOSVENyU5NSVENyVBQS0lRDclQTglRDclOUIlRDclOTE=

using namespace Awesomium;
namespace cm = CommandLineProcessing;

const std::string currentDateTime() {
	time_t     now = time(0);
	struct tm  tstruct;
	char       buf[80];
	tstruct = *localtime(&now);
	strftime(buf, sizeof(buf), "%d%m%Y_%H%M", &tstruct);
	return buf;
}

int main(int argc, char** argv)
{

	//Define args and project settings:
	clock_t tStart = clock();
	int exitCode = 0;
	cm::ArgvParser cmd;
	bool enable_debug = false;

	std::string target = URL_TARGET;
	WebString targetReady;
	std::string payload = "";
	std::string BasePayload = "";
	std::string outfolder = OUT_FOLDER;
	bool savetoout = false;
	std::string nameforscreen = "";
	std::string namefordump = "";
	WebString returnString = WSLit("");

	cmd.addErrorCode(PEC_SUCCESS, "Success");
	cmd.addErrorCode(PEC_ERR_OPTIONS, "Option Error");
	cmd.addErrorCode(PEC_ERR_PAYLOAD_BASE, "Payload Base64 Error");
	cmd.addErrorCode(PEC_ERR_TARGET_BASE, "Target Base64 Error");
	cmd.addErrorCode(PEC_ERR_PAYLOAD_EXEC, "Payload Execution Error");
	cmd.setIntroductoryDescription("DAAT custom scraper: " + std::string(SCRAPER_VERSION) + " - By: " + std::string(SCRAPER_AUTHOR));
	cmd.setHelpOption("h", "help", "Prints help page");
	cmd.defineOption("debug", "enable debug.", cm::ArgvParser::NoOptionAttribute);
	cmd.defineOption("target", "The target url to scrape - its base64 encoded", cm::ArgvParser::OptionRequiresValue);
	cmd.defineOption("payload", "The base 64 to payload to deploy.", cm::ArgvParser::OptionRequiresValue);
	cmd.defineOption("out", "Put results in local folder to.", cm::ArgvParser::OptionRequiresValue);
	int result = cmd.parse(argc, argv);

	//Parse and set Argu:
	if (result != cm::ArgvParser::NoParserError)
	{
		if (result == cm::ArgvParser::ParserHelpRequested) {
			std::cout << cmd.parseErrorDescription(result);
			exitCode = PEC_SUCCESS;
		} else {
			std::cout << "ERROR: Check the options please.";
			//std::cout << cmd.parseErrorDescription(result);
			exitCode = PEC_ERR_OPTIONS;
		}
	} else {
		//Set console args:
		if (cmd.foundOption("debug")) { enable_debug = true; }
		if (cmd.foundOption("target")) { target = cmd.optionValue("target").c_str(); }
		if (cmd.foundOption("payload")) { payload = std::string(cmd.optionValue("payload").c_str()); }
		if (cmd.foundOption("out")) { outfolder = std::string(cmd.optionValue("out").c_str()); savetoout = true; }
	}
	if (result == cm::ArgvParser::ParserHelpRequested) {
		system("pause");
		exit(exitCode);
	}

	if (enable_debug) {
		std::cout << "****************************************************************" << std::endl
			<< "* DAAT Custom Scraper Build                                    *" << std::endl
			<< "****************************************************************" << std::endl << std::endl;
	}

	//BASE64 handling:
	if (exitCode == PEC_SUCCESS && payload != "") {
		BasePayload = payload;
		std::vector<BYTE> PayloadVecEncoded = base64_decode(payload);
		if (!PayloadVecEncoded.empty())
			payload = std::string(PayloadVecEncoded.begin(), PayloadVecEncoded.end());
		else
			exitCode = PEC_ERR_PAYLOAD_BASE;
	}
	if (exitCode == PEC_SUCCESS && target != "") {
		std::vector<BYTE> TargetVecEncoded = base64_decode(target);
		if (!TargetVecEncoded.empty()) {
			target = std::string(TargetVecEncoded.begin(), TargetVecEncoded.end());
			targetReady = WSLit(target.c_str());
		}
		else
			exitCode = PEC_ERR_TARGET_BASE;
	}
	
	//Run Scraper:
	if (exitCode == PEC_SUCCESS) {

		// Create the WebCore singleton with default configuration will be shared between the scrapers:
		WebCore* webcore = WebCore::Initialize(WebConfig());
		WebView* view = webcore->CreateWebView(WEB_WIDTH, WEB_HEIGHT);

		//The Scrapers:
		Scrape scrape(webcore, view, targetReady);
		scrape.LoadToView(scrape.url);
		namefordump = outfolder + currentDateTime() + "ScrapedResult.txt";
		nameforscreen = outfolder + currentDateTime() + "ScrapedScreen.jpeg";
		if (savetoout) {
			scrape.getAndSaveScreen(WSLit(nameforscreen.c_str()));
		}
		int ExecRes = scrape.deliverPayload(payload, &returnString);
		if (ExecRes != 0) { exitCode = PEC_ERR_PAYLOAD_EXEC; }
		//ClenUp
		view->Destroy();
		WebCore::Shutdown();
	}

	//Measure Execution:
	clock_t tEnd = clock();
	int sizeForExec = std::snprintf(nullptr, 0, "%.2fs, %dms", (double)(tEnd - tStart) / CLOCKS_PER_SEC, (tEnd - tStart) / (CLOCKS_PER_SEC / 1000));
	std::string outputExecTime(sizeForExec + 1, '\0');
	std::sprintf(&outputExecTime[0], "%.2fs, %dms", (double)(tEnd - tStart) / CLOCKS_PER_SEC, (tEnd - tStart) / (CLOCKS_PER_SEC / 1000));
	
	//Write the Dump file to disk
	if (savetoout) {
		std::ofstream outDump(namefordump);
		if (!outDump) { // in case the file stream is broken -> report it and don't write.
			exitCode = PEC_ERR_DUMP_WRITE;
		} else {
			//Write the file:
			std::string resultDecoded = "";
			if (!returnString.IsEmpty()) {
				std::vector<BYTE> decodedResultData = base64_decode(Awesomium::ToString(returnString));
				resultDecoded = std::string(decodedResultData.begin(), decodedResultData.end());
			}
			outDump
				<< "************************************************************************************" << std::endl
				<< " DAAT Scraper Dump. -> created:" << currentDateTime() << "  Version: " << SCRAPER_VERSION << std::endl
				<< "************************************************************************************" << std::endl << std::endl
				<< " Option used:" << std::endl
				<< "     - Target    -> " << (target.size() > 100 ? target.substr(0, 100) + "..." : target) << std::endl
				<< "     - Payload   -> " << (BasePayload.size() > 40 ? BasePayload.substr(0, 40) + "..." : BasePayload) << std::endl
				<< "     - Outfolder -> " << nameforscreen << std::endl
				<< "     - Execution -> " << outputExecTime << std::endl
				<< "     - ExitCode  -> " << exitCode << std::endl
				<< "     - Result    -> " << ((returnString.length() > 30) ? (Awesomium::ToString(returnString).substr(0, 30) + "...") : ToString(returnString)) << std::endl << std::endl
				<< std::endl << std::endl << std::endl
				<< "@base@result@start@" << std::endl
				<< (returnString.IsEmpty() ? "none" : ToString(returnString)) << std::endl
				<< "@base@result@end@" << std::endl << std::endl << std::endl
				<< "@text@result@start@" << std::endl
				<< (returnString.IsEmpty() ? "none" : resultDecoded) << std::endl
				<< "@text@result@end@" << std::endl << std::endl << std::endl
				<< "@text@target@start@" << std::endl
				<< target << std::endl
				<< "@text@target@end@" << std::endl << std::endl << std::endl
				<< "@base@payload@start@" << std::endl
				<< (BasePayload.empty() ? "none" : BasePayload) << std::endl
				<< "@base@payload@end@" << std::endl << std::endl << std::endl
				<< "@text@payload@start@" << std::endl
				<< (payload.empty() ? "none" : payload) << std::endl
				<< "@text@payload@end@";
			outDump.close();
		}
	}

	// Console summary in case of debug session.
	// Otherwise only results are passed.
	if (enable_debug) {
		//Display the debug summary:
		std::cout << " * Option used:" << std::endl
			<< "\t\t- Target    -> " << target << std::endl
			<< "\t\t- Payload   -> " << (payload.size() > 15 ? payload.substr(0, 15) + "..." : payload) << std::endl
			<< "\t\t- Outfolder -> " << nameforscreen << std::endl
			<< "\t\t- Execution -> " << outputExecTime << std::endl
			<< "\t\t- ExitCode  -> " << exitCode << std::endl
			<< "\t\t- Result    -> " << ((returnString.length() > 40) ? (Awesomium::ToString(returnString).substr(0, 40) + "...") : ToString(returnString)) << std::endl << std::endl
			<< "****************************************************************" << std::endl << std::endl;
		system("PAUSE");
	} else {
		//Display the output result:
		if (exitCode == 0) {
			std::cout << returnString;
		} else {
			std::cout << "E:" << exitCode;
		}
	}

    return 0;
}

/*

BASE64 Example:

//std::string str = "decode : function b64DecodeUnicode(str) { return decodeURIComponent(Array.prototype.map.call(atob(str), function(c) { return '%' + ('00' + c.charCodeAt(0).toString(16)).slice(-2);";
//std::vector<BYTE> myData(str.begin(), str.end());
//std::string encodedData = base64_encode(&myData[0], myData.size());
//std::vector<BYTE> decodedData = base64_decode(encodedData);
//std::string decodedstr(decodedData.begin(), decodedData.end());

*/