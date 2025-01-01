# GitHub Contributions Generator Guide

This guide explains how to create GitHub contributions across the entire year to populate your contribution graph.

## Quick Start

### Simple Method (Recommended)
Just double-click or run:
```batch
.\create-contributions.bat
```

This will create commits for the current year with default settings (1-5 commits per day, more on weekdays).

### Advanced Usage

#### Using PowerShell directly:
```powershell
# Default (current year, 1-5 commits per day)
.\generate-year-contributions.ps1

# Specific year
.\generate-year-contributions.ps1 -Year 2025

# Custom commit range
.\generate-year-contributions.ps1 -Year 2025 -CommitsPerDay 2 -MaxCommitsPerDay 5

# Only weekdays
.\generate-year-contributions.ps1 -Year 2025 -WeekdaysOnly

# Preview without creating commits (dry run)
.\generate-year-contributions.ps1 -Year 2025 -DryRun
```

#### Using Batch file:
```batch
# Default settings
.\generate-year-contributions.bat

# With parameters
.\generate-year-contributions.bat -Year 2025 -CommitsPerDay 3 -MaxCommitsPerDay 7
```

## Parameters

- **-Year**: The year to generate contributions for (default: current year)
- **-CommitsPerDay**: Minimum commits per day (default: 1)
- **-MaxCommitsPerDay**: Maximum commits per day (default: 5)
- **-WeekdaysOnly**: Only create commits on weekdays
- **-WeekendsOnly**: Only create commits on weekends
- **-DryRun**: Preview what would be created without actually creating commits

## How It Works

1. The script generates commits for every day of the specified year
2. It creates minimal changes in files (like README.md, composer.json, package.json)
3. Each commit is backdated to a specific date and time
4. More commits are created on weekdays, fewer on weekends (by default)
5. Commit messages are randomly selected from a pool of realistic messages

## After Running

1. **Review your commits:**
   ```bash
   git log --oneline --graph
   ```

2. **Check contribution count:**
   ```bash
   git log --oneline | Measure-Object -Line
   ```

3. **Push to GitHub:**
   ```bash
   git push origin master
   ```
   (Replace `master` with your branch name if different)

## Important Notes

- ⚠️ **This creates many commits** - Pushing may take some time
- ⚠️ **Use responsibly** - These are artificial contributions
- ✅ **Safe to use** - Only modifies safe files (README, config files)
- ✅ **Can be undone** - You can reset if needed (use `git reset` carefully)

## Troubleshooting

### Script won't run
- Make sure PowerShell execution policy allows scripts:
  ```powershell
  Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser
  ```

### Commits not showing on GitHub
- Make sure you've pushed to the remote repository
- GitHub may take a few minutes to update the contribution graph
- Check that commits are on the correct branch

### Too many/few commits
- Adjust `-CommitsPerDay` and `-MaxCommitsPerDay` parameters
- Use `-WeekdaysOnly` to reduce weekend commits
- Use `-DryRun` first to preview

## Example Output

After running, you'll see:
```
==========================================
Starting year-long contribution generation
Year: 2025
Commits per day: 1
Max commits per day: 5
==========================================
Changed to repository directory: C:\laragon\www\cms
Generating commits from 2025-01-01 to 2025-12-31
This may take a while...
Created commit #1 on 2025-01-01 09:15:23: feat: add new feature
Created commit #2 on 2025-01-01 14:32:11: docs: update documentation
...
==========================================
Contribution generation completed!
Total commits created: 1247
==========================================
```

## Support

If you encounter issues:
1. Check the `auto-commit.log` file for detailed logs
2. Try running with `-DryRun` first to preview
3. Make sure you're in a git repository
4. Ensure you have write permissions

