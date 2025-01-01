# Generate Year-Long GitHub Contributions Script
# This script creates commits across the entire year with backdated timestamps
# to populate your GitHub contribution graph

param(
    [int]$Year = (Get-Date).Year,
    [int]$CommitsPerDay = 1,
    [int]$MaxCommitsPerDay = 5,
    [switch]$WeekendsOnly = $false,
    [switch]$WeekdaysOnly = $false,
    [switch]$DryRun = $false
)

# Configuration
$REPO_PATH = "C:\laragon\www\cms"
$LOG_FILE = "$REPO_PATH\auto-commit.log"

# Function to write to log file
function Write-Log {
    param([string]$Message)
    $timestamp = Get-Date -Format "yyyy-MM-dd HH:mm:ss"
    $logEntry = "[$timestamp] $Message"
    Write-Host $logEntry
    Add-Content -Path $LOG_FILE -Value $logEntry
}

# Function to generate random commit message
function Get-RandomCommitMessage {
    $messages = @(
        "chore: update project documentation and code comments",
        "docs: improve code documentation and inline comments",
        "style: enhance code formatting and consistency",
        "refactor: optimize code structure and organization",
        "feat: add minor improvements and enhancements",
        "fix: resolve code formatting and style issues",
        "perf: optimize performance and code efficiency",
        "ci: update development environment configuration",
        "build: improve build process and dependencies",
        "test: enhance test coverage and validation",
        "security: update security configurations and settings",
        "maintenance: perform routine code maintenance",
        "update: refresh project dependencies and configurations",
        "improve: enhance overall code quality and standards",
        "clean: clean up code and remove unused elements",
        "feat: implement new feature",
        "fix: bug fix and error handling",
        "refactor: code refactoring",
        "docs: update documentation",
        "test: add unit tests",
        "perf: performance optimization",
        "style: code style improvements",
        "chore: update dependencies"
    )
    return $messages | Get-Random
}

# Function to create a minimal change in a file
function Create-MinimalChange {
    param([DateTime]$CommitDate)
    
    $files = @(
        "readme.md",
        "composer.json",
        "package.json"
    )

    foreach ($file in $files) {
        $filePath = Join-Path $REPO_PATH $file
        if (Test-Path $filePath) {
            try {
                $content = Get-Content $filePath -Raw
                $timestamp = $CommitDate.ToString("yyyy-MM-dd HH:mm:ss")
                $randomNum = Get-Random -Minimum 1000 -Maximum 9999

                if ($file -eq "readme.md") {
                    # Add a maintenance note at the end
                    $newContent = $content + "`n`n<!-- Auto-update: $timestamp - $randomNum -->"
                } elseif ($file -eq "composer.json") {
                    # Add a comment in description or update version
                    if ($content -match '"description"') {
                        $newContent = $content -replace '("description":\s*"[^"]*")', "`$1 // Updated: $timestamp"
                    } else {
                        $newContent = $content + "`n// Updated: $timestamp - $randomNum"
                    }
                } elseif ($file -eq "package.json") {
                    # Similar to composer.json
                    if ($content -match '"description"') {
                        $newContent = $content -replace '("description":\s*"[^"]*")', "`$1 // Updated: $timestamp"
                    } else {
                        $newContent = $content + "`n// Updated: $timestamp - $randomNum"
                    }
                } else {
                    $newContent = $content + "`n// Updated: $timestamp - $randomNum"
                }

                Set-Content -Path $filePath -Value $newContent -NoNewline
                return $true
            }
            catch {
                continue
            }
        }
    }
    
    # If no file was found, create a dummy file
    $dummyFile = Join-Path $REPO_PATH "contributions-dummy.txt"
    $timestamp = $CommitDate.ToString("yyyy-MM-dd HH:mm:ss")
    $randomNum = Get-Random -Minimum 1000 -Maximum 9999
    Add-Content -Path $dummyFile -Value "Contribution entry: $timestamp - $randomNum"
    return $true
}

# Function to create a commit with a specific date
function Create-DatedCommit {
    param(
        [DateTime]$CommitDate,
        [int]$CommitNumber = 1
    )
    
    try {
        # Create minimal change
        $changeMade = Create-MinimalChange -CommitDate $CommitDate
        if (-not $changeMade) {
            Write-Log "Warning: Could not create change for $($CommitDate.ToString('yyyy-MM-dd'))"
            return $false
        }

        # Stage changes
        $addOutput = git add . 2>&1
        if ($LASTEXITCODE -ne 0) {
            Write-Log "Warning: Failed to stage changes: $addOutput"
            return $false
        }

        # Generate commit message
        $message = Get-RandomCommitMessage

        # Format date for git (RFC 2822 format)
        $dateStr = $CommitDate.ToString("yyyy-MM-dd HH:mm:ss")
        $rfcDate = $CommitDate.ToString("ddd, dd MMM yyyy HH:mm:ss +0000")
        
        # Set environment variables for git commit date
        $env:GIT_AUTHOR_DATE = $rfcDate
        $env:GIT_COMMITTER_DATE = $rfcDate

        if ($DryRun) {
            Write-Log "[DRY RUN] Would commit on $dateStr with message: $message"
            # Revert the change for dry run
            git reset HEAD . 2>&1 | Out-Null
            git checkout -- . 2>&1 | Out-Null
            return $true
        } else {
            # Create commit with backdated timestamp using environment variables
            $commitOutput = git commit -m $message 2>&1
            if ($LASTEXITCODE -eq 0) {
                Write-Log "Created commit #${CommitNumber} on ${dateStr}: ${message}"
                return $true
            } else {
                Write-Log "Warning: Commit failed for ${dateStr} - ${commitOutput}"
                # Reset staging area if commit failed
                git reset HEAD . 2>&1 | Out-Null
                return $false
            }
        }
    }
    catch {
        Write-Log "Error creating commit for $($CommitDate.ToString('yyyy-MM-dd')): $($_.Exception.Message)"
        return $false
    }
}

# Main execution
try {
    Write-Log "=========================================="
    Write-Log "Starting year-long contribution generation"
    Write-Log "Year: $Year"
    Write-Log "Commits per day: $CommitsPerDay"
    Write-Log "Max commits per day: $MaxCommitsPerDay"
    if ($DryRun) {
        Write-Log "DRY RUN MODE - No actual commits will be created"
    }
    Write-Log "=========================================="

    # Change to repository directory
    Set-Location $REPO_PATH
    Write-Log "Changed to repository directory: $REPO_PATH"

    # Check if we're in a git repository
    if (-not (Test-Path ".git")) {
        Write-Log "Error: Not a git repository"
        exit 1
    }

    # Generate dates for the entire year
    $startDate = Get-Date -Year $Year -Month 1 -Day 1
    $endDate = Get-Date -Year $Year -Month 12 -Day 31
    
    $currentDate = $startDate
    $totalCommits = 0
    $commitNumber = 1

    Write-Log "Generating commits from $($startDate.ToString('yyyy-MM-dd')) to $($endDate.ToString('yyyy-MM-dd'))"
    Write-Log "This may take a while..."

    while ($currentDate -le $endDate) {
        $dayOfWeek = $currentDate.DayOfWeek
        
        # Skip weekends if WeekdaysOnly is set
        if ($WeekdaysOnly -and ($dayOfWeek -eq "Saturday" -or $dayOfWeek -eq "Sunday")) {
            $currentDate = $currentDate.AddDays(1)
            continue
        }
        
        # Skip weekdays if WeekendsOnly is set
        if ($WeekendsOnly -and ($dayOfWeek -ne "Saturday" -and $dayOfWeek -ne "Sunday")) {
            $currentDate = $currentDate.AddDays(1)
            continue
        }

        # Determine number of commits for this day (more on weekdays, fewer on weekends)
        $commitsToday = $CommitsPerDay
        if ($dayOfWeek -eq "Saturday" -or $dayOfWeek -eq "Sunday") {
            # 50% chance of commits on weekends, fewer commits
            if ((Get-Random -Maximum 2) -eq 0) {
                $commitsToday = [Math]::Max(1, [Math]::Floor($CommitsPerDay / 2))
            } else {
                $commitsToday = 0
            }
        } else {
            # Weekdays: random between CommitsPerDay and MaxCommitsPerDay
            $commitsToday = Get-Random -Minimum $CommitsPerDay -Maximum ($MaxCommitsPerDay + 1)
        }

        # Create commits for this day
        for ($i = 0; $i -lt $commitsToday; $i++) {
            # Random time during the day (between 9 AM and 6 PM)
            $hour = Get-Random -Minimum 9 -Maximum 18
            $minute = Get-Random -Minimum 0 -Maximum 60
            $second = Get-Random -Minimum 0 -Maximum 60
            
            $commitDateTime = Get-Date -Year $currentDate.Year `
                                      -Month $currentDate.Month `
                                      -Day $currentDate.Day `
                                      -Hour $hour `
                                      -Minute $minute `
                                      -Second $second

            $success = Create-DatedCommit -CommitDate $commitDateTime -CommitNumber $commitNumber
            if ($success) {
                $totalCommits++
                $commitNumber++
            }
        }

        # Progress indicator
        if ($currentDate.Day -eq 1 -or $currentDate.Day -eq 15) {
            Write-Log "Progress: Processed up to $($currentDate.ToString('yyyy-MM-dd')) - Created $totalCommits commits so far"
        }

        $currentDate = $currentDate.AddDays(1)
    }

    Write-Log "=========================================="
    Write-Log "Contribution generation completed!"
    Write-Log "Total commits created: $totalCommits"
    Write-Log "=========================================="
    
    if (-not $DryRun) {
        # Get current branch name
        $currentBranch = git branch --show-current
        if (-not $currentBranch) {
            $currentBranch = "master"
        }
        
        Write-Log ""
        Write-Log "Next steps:"
        Write-Log "1. Review your commits: git log --oneline --graph --all"
        Write-Log "2. Check contribution count: git log --oneline | Measure-Object -Line"
        Write-Log "3. Push to GitHub: git push origin $currentBranch"
        Write-Log ""
        Write-Log "Note: If you want to push all commits at once, use:"
        Write-Log "   git push origin $currentBranch"
        Write-Log ""
        Write-Log "Warning: Pushing many commits may take some time."
        Write-Log "You can also push in batches if needed."
    }

} catch {
    Write-Log "Error during contribution generation: $($_.Exception.Message)"
    Write-Log $_.ScriptStackTrace
    exit 1
}

