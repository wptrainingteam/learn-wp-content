# What the future of  development could look like

**Date:** 2025-05-15

**Status:** draft

I sit down in front of my PC, coffee in hand, open my personal AI chat client (let's call him Bob) and let Bob know which project I'm working on today.

Automatically, Bob will fetch all the newest issues from the [public version control repository](https://github.com/github/github-mcp-server) for me to review. Bob will suggest labels based on the contents of each issue, which I'll either confirm or alter, asking Bob to assign certain issues to myself, with priority levels, and the rest to other relevant members of my team. Where needed, I'll ask Bob to ask clarifying questions on issues, provide further feedback, or find and link to relevant documentation.

Next I'll get Bob to list any merge requests I need to review. Bob will automatically pull the code for me for each request, and open a code review window for me to review. An AI code review agent (let's call her Shirley) will have already left her report and feedback, which I can see. I'll review all this and leave any additional feedback where needed. If I'm the final reviewer, and it looks good, I'll mark it as ready to be merged.

On any code that's ready, Shirley will trigger the action to merge the code, which will automatically run the deployment pipeline, and report back if successful or if there's a failure. 

If there's a failure, Shirley will automatically roll back the change, and alert Bob to alert me of the failure. I can then either ask Bob to pause whatever I'm currently doing, or make that the next task on my list. 