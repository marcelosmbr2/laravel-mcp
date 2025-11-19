<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;

class DeleteUserTool extends Tool
{
    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    /**
     * The tool's description.
     */
    protected string $description = <<<'MARKDOWN'
        Find a user by ID and delete them from the system.
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

        $userName = $user->name;
        $userEmail = $user->email;
        $userId = $user->id;

        $user->delete();

        return Response::text(
            "User deleted successfully!\n\n" .
            "ID: {$userId}\n" .
            "Name: {$userName}\n" .
            "Email: {$userEmail}\n" .
            "Deleted at: " . now()->format('Y-m-d H:i:s')
        );
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
                ->description('The ID of the user to delete')
                ->required(),
        ];
    }
}