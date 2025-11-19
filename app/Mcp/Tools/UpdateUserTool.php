<?php

namespace App\Mcp\Tools;

use Illuminate\JsonSchema\JsonSchema;
use Laravel\Mcp\Request;
use Laravel\Mcp\Response;
use Laravel\Mcp\Server\Tool;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UpdateUserTool extends Tool
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
        Find a user by ID and update their information (name, email, and/or password).
    MARKDOWN;

    /**
     * Handle the tool request.
     */
    public function handle(Request $request): Response
    {
        $validated = $request->validate([
            'id' => 'required|integer|exists:users,id',
            'name' => 'sometimes|string|max:255',
            'email' => [
                'sometimes',
                'string',
                'email',
                'max:255',
                Rule::unique('users')->ignore($request->input('id')),
            ],
            'password' => 'sometimes|string|min:8',
        ]);

        $user = $this->userModel->findOrFail($validated['id']);

        $oldName = $user->name;
        $oldEmail = $user->email;

        $updateData = [];

        if (isset($validated['name'])) {
            $updateData['name'] = $validated['name'];
        }

        if (isset($validated['email'])) {
            $updateData['email'] = $validated['email'];
        }

        if (isset($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        $user->refresh();

        $output = "User updated successfully!\n\n";
        $output .= "ID: {$user->id}\n";
        $output .= "Name: {$user->name}" . ($oldName !== $user->name ? " (changed from: {$oldName})" : "") . "\n";
        $output .= "Email: {$user->email}" . ($oldEmail !== $user->email ? " (changed from: {$oldEmail})" : "") . "\n";
        
        if (isset($validated['password'])) {
            $output .= "Password: Updated\n";
        }
        
        $output .= "Updated at: {$user->updated_at->format('Y-m-d H:i:s')}\n";

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
                ->description('The ID of the user to update')
                ->required(),
            
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