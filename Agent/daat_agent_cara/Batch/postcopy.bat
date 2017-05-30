SET sorcuDir="C:\Program Files (x86)\Awesomium Technologies LLC\Awesomium SDK\1.7.5.1\build\bin"
SET destDir="%~1 "
robocopy %sorcuDir% %destDir% avformat-53.dll avutil-51.dll awesomium.dll awesomium_process.exe icudt.dll libEGL.dll libGLESv2.dll xinput9_1_0.dll
if %errorlevel% leq 1 exit 0 else exit %errorlevel%

