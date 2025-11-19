<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CreateUserTool extends Tool
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
        Create a new user in the system with name, email and password.
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = $this->userModel->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return Response::text(
            "User created successfully!\n\n" .
            "ID: {$user->id}\n" .
            "Name: {$user->name}\n" .
            "Email: {$user->email}\n" .
            "Created at: {$user->created_at->format('Y-m-d H:i:s')}"
        );
    }

    /**
     * Get the tool's input schema.
     * Specify what arguments they accept from AI clients
     *
     * @return array<string, \Illuminate\JsonSchema\JsonSchema>
     */
    public function schema(JsonSchema $schema): array
    {
        return [
            'name' => $schema->string()
                ->description('The name of the user')
                ->required(),
            
            'email' => $schema->string()
                ->format('email')
                ->description('The email address of the user')
                ->required(),
            
            'password' => $schema->string()
                ->description('The password for the user (minimum 8 characters)')
                ->required(),
        ];
    }
}