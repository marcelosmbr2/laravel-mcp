<?php

namespace App\Mcp\Resources;

use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Resource;

class UserServerGuideResource extends Resource
{
    /**
     * The resource's description.
     */
    protected string $description = <<<'MARKDOWN'
        Quick reference guide for AI assistants using MCP Server user management tools.
    MARKDOWN;

    /**
     * Handle the resource request.
     */
    public function handle(Request $request): Response
    {
        $guide = <<<'GUIDE'
MCP Server - AI Quick Reference
================================

AVAILABLE TOOLS
---------------

create_user
  Parameters: name (required), email (required), password (required, min:8)
  Returns: User ID, name, email, created_at

list_users
  Parameters: per_page (optional, default:15, max:100), page (optional, default:1), search (optional)
  Returns: Paginated list with total count, current page info, and user details

show_user
  Parameters: id (required)
  Returns: Full user details including ID, name, email, email_verified_at, created_at, updated_at

update_user
  Parameters: id (required), name (optional), email (optional), password (optional, min:8)
  Returns: Updated user info with change indicators

delete_user
  Parameters: id (required)
  Returns: Confirmation with deleted user info

VALIDATION RULES
----------------
• Email must be unique across all users (except when updating same user)
• Password minimum 8 characters
• All IDs must exist in database
• Email must be valid format

WORKFLOW LOGIC
--------------
Before delete/update: Always verify user exists with show_user
For search queries: Use list_users with search parameter
For pagination: Use per_page and page parameters in list_users
After create/update: Show confirmation with returned data

ERROR HANDLING
--------------
Non-existent ID: Tool returns "not found" error
Duplicate email: Validation fails with unique constraint error
Invalid format: Validation fails with specific field error

GUIDE;

        return Response::text($guide);
    }
}