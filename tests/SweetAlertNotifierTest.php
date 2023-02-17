<?php

use Mockery as m;
use PHPUnit\Framework\TestCase;
use UxWeb\SweetAlert\SessionStore;
use UxWeb\SweetAlert\SweetAlertNotifier;

class SweetAlertNotifierTest extends TestCase
{
    use \Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;

    /** @test */
    public function text_is_empty_by_default(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message();

        $this->assertEquals('', $notifier->getConfig('text'));
    }

    /** @test */
    public function default_timer_is_2500_milliseconds(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Good News!');

        $this->assertEquals(2500, $notifier->getConfig('timer'));
    }

    /** @test */
    public function buttons_config_is_false_by_default(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Good News!');

        $buttonsConfig = [
            'confirm' => false,
            'cancel' => false,
        ];
        $this->assertEquals($buttonsConfig, $notifier->getConfig('buttons'));
    }

    /** @test */
    public function first_parameter_of_alert_message_is_the_config_text(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Hello World!');

        $this->assertEquals('Hello World!', $notifier->getConfig('text'));
    }

    /** @test */
    public function title_key_is_not_present_in_config_when_alert_title_is_not_set(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Hello World!');

        $this->assertArrayNotHasKey('title', $notifier->getConfig());
    }

    /** @test */
    public function second_parameter_of_alert_message_is_the_config_title(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Hello World!', 'This is the title');

        $this->assertEquals('This is the title', $notifier->getConfig('title'));
    }

    /** @test */
    public function third_parameter_of_alert_message_is_the_config_icon(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Hello World!', 'This is the title', 'info');

        $this->assertEquals('info', $notifier->getConfig('icon'));
    }

    /** @test */
    public function icon_key_is_not_present_in_config_when_alert_icon_is_not_set(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Hello World!', 'This is the title');

        $this->assertArrayNotHasKey('icon', $notifier->getConfig());
    }

    /** @test */
    public function it_flashes_config_for_a_basic_alert(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Basic Alert!', 'Alert');

        $actualArray = $notifier->getConfig();

        $expectedConfig = [
            'text' => 'Basic Alert!',
            'title' => 'Alert',
        ];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        unset($notifier);

        $session->shouldHaveReceived('flash')->with('sweet_alert.title', $expectedConfig['title']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.text', $expectedConfig['text']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.alert', \Hamcrest\Text\IsEmptyString::isNonEmptyString());
    }

    /** @test */
    public function it_flashes_config_for_a_info_alert(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->info('Info Alert!', 'Alert');

        $actualArray = $notifier->getConfig();

        $expectedConfig = [
            'text' => 'Info Alert!',
            'title' => 'Alert',
            'icon' => 'info',
        ];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        unset($notifier);

        $session->shouldHaveReceived('flash')->with('sweet_alert.title', $expectedConfig['title']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.text', $expectedConfig['text']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.icon', $expectedConfig['icon']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.alert', \Hamcrest\Text\IsEmptyString::isNonEmptyString());
    }

    /** @test */
    public function it_flashes_config_for_a_success_alert(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->success('Well Done!', 'Success!');

        $actualArray = $notifier->getConfig();

        $expectedConfig = [
            'title' => 'Success!',
            'text' => 'Well Done!',
            'icon' => 'success',
        ];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        unset($notifier);

        $session->shouldReceive('flash')->with('sweet_alert.title', $expectedConfig['title']);
        $session->shouldReceive('flash')->with('sweet_alert.text', $expectedConfig['text']);
        $session->shouldReceive('flash')->with('sweet_alert.icon', $expectedConfig['icon']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.alert', \Hamcrest\Matchers::isNonEmptyString());
    }

    /** @test */
    public function it_flashes_config_for_a_warning_alert(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->warning('Hey cowboy!', 'Watch Out!');

        $actualArray = $notifier->getConfig();

        $expectedConfig = [
            'title' => 'Watch Out!',
            'text' => 'Hey cowboy!',
            'icon' => 'warning',
        ];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        unset($notifier);

        $session->shouldReceive('flash')->with('sweet_alert.title', $expectedConfig['title']);
        $session->shouldReceive('flash')->with('sweet_alert.text', $expectedConfig['text']);
        $session->shouldReceive('flash')->with('sweet_alert.icon', $expectedConfig['icon']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.alert', \Hamcrest\Matchers::isNonEmptyString());
    }

    /** @test */
    public function it_flashes_config_for_a_error_alert(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->error('Something wrong happened!', 'Whoops!');

        $actualArray = $notifier->getConfig();

        $expectedConfig = [
            'title' => 'Whoops!',
            'text' => 'Something wrong happened!',
            'icon' => 'error',
        ];


        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        unset($notifier);

        $session->shouldHaveReceived('flash')->with('sweet_alert.title', $expectedConfig['title']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.text', $expectedConfig['text']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.icon', $expectedConfig['icon']);
        $session->shouldHaveReceived('flash')->with('sweet_alert.alert', \Hamcrest\Matchers::isNonEmptyString());
    }

    /** @test */
    public function autoclose_can_be_customized_for_an_alert_message(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Hello!', 'Alert')->autoclose(2000);

        $this->assertEquals(2000, $notifier->getConfig('timer'));
        unset($notifier);
        $session->shouldHaveReceived('flash')->with('sweet_alert.timer', 2000);
    }

    /** @test */
    public function timer_option_is_not_present_in_config_when_using_a_persistent_alert(): void
    {
        $session = m::mock(SessionStore::class);
        $session->shouldReceive('flash')->atLeast(1);
        $session->shouldReceive('remove')->atLeast(1);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('Please, read with care!', 'Alert')->persistent('Got it!');

        $this->assertArrayNotHasKey('timer', $notifier->getConfig());
    }

    /** @test */
    public function persistent_alert_has_only_a_confirm_button_by_default(): void
    {
        $session = m::mock(SessionStore::class);
        $session->shouldReceive('flash')->atLeast(1);
        $session->shouldReceive('remove')->atLeast(1);
        $notifier = new SweetAlertNotifier($session);

        $notifier->warning('Are you sure?', 'Delete all posts')->persistent('I\'m sure');

        $actualArray = $notifier->getConfig('buttons')['confirm'];

        $expectedConfig = [
            'text' => 'I\'m sure',
            'visible' => true,
        ];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

    }

    /** @test */
    public function it_will_add_the_content_option_to_config_when_using_an_html_alert(): void
    {
        $session = m::mock(SessionStore::class);
        $session->shouldReceive('flash')->atLeast(1);
        $session->shouldReceive('remove')->atLeast(1);
        $notifier = new SweetAlertNotifier($session);

        $notifier->message('<strong>This should be bold!</strong>', 'Alert')->html();

        $this->assertEquals('<strong>This should be bold!</strong>', $notifier->getConfig('content'));
    }

    /** @test */
    public function allows_to_configure_a_confirm_button_for_an_alert(): void
    {
        $session = m::mock(SessionStore::class);
        $session->shouldReceive('flash')->atLeast(1);
        $session->shouldReceive('remove')->atLeast(1);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Basic Alert!', 'Alert')->confirmButton('help!');

        $actualArray = $notifier->getConfig('buttons')['confirm'];

        $expectedConfig = [
            'text' => 'help!',
            'visible' => true,
        ];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        $this->assertFalse($notifier->getConfig('closeOnClickOutside'));
    }

    /** @test */
    public function allows_to_configure_a_cancel_button_for_an_alert(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Basic Alert!', 'Alert')->cancelButton('Cancel!');

        $actualArray = $notifier->getConfig('buttons')['cancel'];

        $expectedConfig = ['text' => 'Cancel!', 'visible' => true];

        foreach ($expectedConfig as $key => $value) {
            $this->assertArrayHasKey($key, $actualArray);
            $this->assertSame($value, $actualArray[$key]);
        }

        $this->assertFalse($notifier->getConfig('closeOnClickOutside'));
    }

    /** @test */
    public function close_on_click_outside_config_can_be_enabled(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Basic Alert!', 'Alert')->closeOnClickOutside();

        $this->assertTrue($notifier->getConfig('closeOnClickOutside'));
    }

    /** @test */
    public function close_on_click_outside_config_can_be_disabled(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Basic Alert!', 'Alert')->closeOnClickOutside(false);

        $this->assertFalse($notifier->getConfig('closeOnClickOutside'));
    }

    /** @test */
    public function additional_buttons_can_be_added(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Pay with:', 'Payment')->addButton('credit_card', 'Credit Card');
        $notifier->basic('Pay with:', 'Payment')->addButton('paypal', 'Paypal');

        foreach (['credit_card' => 'Credit Card', 'paypal' => 'Paypal'] as $inner_key => $text) {

            $this->assertArrayHasKey($inner_key, $notifier->getConfig('buttons'));

            $actualArray = $notifier->getConfig('buttons')[$inner_key];

            $expectedConfig = [
                'text' => $text,
                'visible' => true,
            ];

            foreach ($expectedConfig as $key => $value) {
                $this->assertArrayHasKey($key, $actualArray);
                $this->assertSame($value, $actualArray[$key]);
            }

        }


        $this->assertFalse($notifier->getConfig('closeOnClickOutside'));
    }

    /** @test */
    public function additional_config_can_be_added_to_configure_alert_message(): void
    {
        $session = m::spy(SessionStore::class);
        $notifier = new SweetAlertNotifier($session);

        $notifier->basic('Basic Alert!', 'Alert')->setConfig(['dangerMode' => true]);

        $this->assertTrue($notifier->getConfig('dangerMode'));
        unset($notifier);
        $session->shouldHaveReceived('flash')->with('sweet_alert.dangerMode', true);
    }
}

/**
 * Get / set the specified configuration value.
 *
 * If an array is passed as the key, we will assume you want to set an array of values.
 *
 * @param array|string $key
 * @param mixed        $default
 *
 * @return mixed|\Illuminate\Config\Repository
 */
function config($key = null, $default = null)
{
    return 2500;
}
