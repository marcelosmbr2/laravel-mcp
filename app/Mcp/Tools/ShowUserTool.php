<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;

class ShowUserTool extends Tool
{
    protected User $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }
    
    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Find and display detailed information about a user by their ID.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:users,id',
        ]);

        $user = $this->userModel->findOrFail($validated['id']);

        $output = "User Details\n";
        $output .= "============\n\n";
        $output .= "ID: {$user->id}\n";
        $output .= "Name: {$user->name}\n";
        $output .= "Email: {$user->email}\n";
        $output .= "Email Verified: " . ($user->email_verified_at ? 'Yes (' . $user->email_verified_at->format('Y-m-d H:i:s') . ')' : 'No') . "\n";
        $output .= "Created At: {$user->created_at->format('Y-m-d H:i:s')}\n";
        $output .= "Updated At: {$user->updated_at->format('Y-m-d H:i:s')}\n";

        return Response::text($output);
    }

    /**
     * Get the tool's input schema.
     * Specify what arguments they accept from AI clients.
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'id' => $schema->integer()
                ->description('The ID of the user to retrieve')
                ->required(),
        ];
    }
}