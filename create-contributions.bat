@echo off
echo ==========================================
echo Create GitHub Contributions for the Year
echo ==========================================
echo.
echo This script will create commits across the entire year
echo to populate your GitHub contribution graph.
echo.
echo Default settings:
echo   - Year: Current year (2025)
echo   - Commits per day: 1-5 (random)
echo   - More commits on weekdays, fewer on weekends
echo.
echo Options you can use:
echo   -Year 2025              : Specify the year
echo   -CommitsPerDay 2        : Minimum commits per day
echo   -MaxCommitsPerDay 5     : Maximum commits per day
echo   -WeekdaysOnly           : Only create commits on weekdays
echo   -DryRun                 : Preview without creating commits
echo.
echo Example:
echo   .\create-contributions.bat
echo   .\create-contributions.bat -Year 2025 -CommitsPerDay 2 -MaxCommitsPerDay 5
echo.
echo.

set /p confirm="Do you want to create contributions for the whole year? (Y/N): "
if /i not "%confirm%"=="Y" (
    echo Cancelled.
    pause
    exit /b 0
)

echo.
echo Starting contribution generation...
echo This may take several minutes depending on the number of commits...
echo.

REM Run the PowerShell script with default parameters
powershell -ExecutionPolicy Bypass -File "%~dp0generate-year-contributions.ps1" %*

echo.
echo ==========================================
echo Script execution completed!
echo ==========================================
echo.
echo Next steps:
echo 1. Review commits: git log --oneline
echo 2. Push to GitHub: git push origin master
echo.
pause

