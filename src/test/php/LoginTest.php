<?php
use PHPUnit\Framework\TestCase;

class LoginTest extends TestCase
{
    protected function setUp(): void
    {
        $_SESSION = [];
        $_POST = [];
        $_GET = [];
    }

    public function testLoginWithValidCredentials()
    {
        $_POST['email'] = 'user@example.com';
        $_POST['password'] = 'password1234';
        $_POST['submit'] = true; // Simulating form submission

        ob_start();
        include __DIR__ . '/../../index.php';
        $output = ob_get_clean(); 

        // Check if the session has been set as expected
        $this->assertEquals('user@example.com', $_SESSION['user_id'] ?? null);
    }

    public function testLoginWithInvalidCredentials()
    {
        $_POST['email'] = 'user@example.com';
        $_POST['password'] = 'wrongpassword';
        $_POST['submit'] = true; // Simulating form submission

        ob_start();
        include __DIR__ . '/../../index.php';
        $output = ob_get_clean(); 

        // Check that no session user_id is set and output contains 'Login failed'
        $this->assertArrayNotHasKey('user_id', $_SESSION);
        $this->assertStringContainsString('Login failed', $output);
    }
}
