@echo off
echo ==========================================
echo Generate Year-Long GitHub Contributions
echo ==========================================
echo.

REM Check if PowerShell is available
powershell -Command "Get-Host" >nul 2>&1
if %errorlevel% neq 0 (
    echo Error: PowerShell is not available
    pause
    exit /b 1
)

echo This script will create commits across the entire year with backdated timestamps.
echo.
echo Options:
echo   -Year 2025              : Specify the year (default: current year)
echo   -CommitsPerDay 2        : Minimum commits per day (default: 1)
echo   -MaxCommitsPerDay 5     : Maximum commits per day (default: 5)
echo   -WeekdaysOnly           : Only create commits on weekdays
echo   -WeekendsOnly            : Only create commits on weekends
echo   -DryRun                  : Preview without creating commits
echo.
echo Example usage:
echo   .\generate-year-contributions.bat
echo   .\generate-year-contributions.ps1 -Year 2025 -CommitsPerDay 2 -MaxCommitsPerDay 5
echo   .\create-contributions.bat
echo.

set /p confirm="Do you want to proceed? (Y/N): "
if /i not "%confirm%"=="Y" (
    echo Cancelled.
    pause
    exit /b 0
)

echo.
echo Starting script...
echo.

REM Run the PowerShell script
powershell -ExecutionPolicy Bypass -File "%~dp0generate-year-contributions.ps1" %*

echo.
echo Script execution completed.
pause

