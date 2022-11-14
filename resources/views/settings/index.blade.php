@extends('layouts.app')

@section('meta-title', __('Settings'))

@section('zerion-wrapper-id', 'settings')

@section('content')
<section class="settings">
    <div class="settings_wrapper">
        <h1 class="settings_title">{{ __('Settings') }}</h1>
        <!-- <p class="settings_title2">Preferences</p> -->

        <div>
            <form action="{{ route('settings.update') }}" method="POST">
                @csrf
                @method('PUT')
                <div class="settings_form_body">                                                                                            
                    <div class="settings_form_group">
                        <label class="settings_form_label">{{ __('Email') }}*</label>
                        <input class="settings_form_input_field @error ('email') is-invalid @enderror" name="email" value="{{ $user->email }}" autocomplete="off">
                        @error('email')
                        <div class="settings_form_error_msg">{{ $message }}</div>
                        @enderror
                    </div>   
                    <div class="settings_form_group">
                        <label class="settings_form_label">{{ __('Password') }}</label>
                        <input type="password" class="settings_form_input_field @error ('password') is-invalid @enderror" name="password" value="" autocomplete="off">
                        @error('password')
                        <div class="settings_form_error_msg">{{ $message }}</div>
                        @enderror
                    </div>                  
                    <div class="settings_form_group">
                        <label class="settings_form_label">{{ __('Name') }}*</label>
                        <input class="settings_form_input_field @error ('name') is-invalid @enderror" name="name" value="{{ $user->name }}" autocomplete="off">
                        @error('name')
                        <div class="settings_form_error_msg">{{ $message }}</div>
                        @enderror
                    </div>           
                    <div class="settings_form_group">
                        <label class="settings_form_label">{{ __('Second Name') }}*</label>
                        <input class="settings_form_input_field @error ('second_name') is-invalid @enderror" name="second_name" value="{{ $userProfile->second_name }}" autocomplete="off">
                        @error('second_name')
                        <div class="settings_form_error_msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="settings_form_group">
                        <label class="settings_form_label">{{ __('Phone') }}*</label>
                        <input class="settings_form_input_field @error ('phone') is-invalid @enderror" name="phone" value="{{ $userProfile->phone }}" autocomplete="off">
                        @error('phone')
                        <div class="settings_form_error_msg">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="settings_form_group">
                        <label class="settings_form_label">{{ __('Telegram') }}</label>
                        <input class="settings_form_input_field @error ('telegram') is-invalid @enderror" name="telegram" value="{{ $userProfile->telegram }}" autocomplete="off">
                        @error('telegram')
                        <div class="settings_form_error_msg">{{ $message }}</div>
                        @enderror
                    </div>                    
                    <div class="settings_form_group" style="align-items: center;">
                        <label class="settings_form_label">{{ __('Avatar') }}</label>
                        <user-ava-upload></user-ava-upload>
                    </div>                    
                </div>            
                <div class="settings_form_footer">
                    <button type="submit" class="settings_form_button">{{ __('Save') }}</button>
                </div>
            </form>          
        </div>            
        <!-- <div class="theme_wrapper">
            <p class="theme_title">Theme</p>
            <div class="theme_radio_inputs_wrapper">
                <div class="theme_radio_input">
                    <input type="radio" class="theme_radio_input_field" hidden id="theme_radio_input1" name="theme_radio_input_name">
                    <label for="theme_radio_input1" class="theme_radio_input_label"></label>
                    <span class="theme_radio_input_title">Light</span>
                </div>
                <div class="theme_radio_input">
                    <input type="radio" class="theme_radio_input_field" hidden id="theme_radio_input2" name="theme_radio_input_name">
                    <label for="theme_radio_input2" class="theme_radio_input_label"></label>
                    <span class="theme_radio_input_title">System</span>
                </div>
                <div class="theme_radio_input">
                    <input type="radio" class="theme_radio_input_field" hidden id="theme_radio_input3" name="theme_radio_input_name">
                    <label for="theme_radio_input3" class="theme_radio_input_label"></label>
                    <span class="theme_radio_input_title">Dark</span>
                </div>
            </div>
        </div>
        <div class="language_wrapper">
            <p class="language_title">Language</p>
            <div class="language_title_icon_wrapper">
                <p class="language_item_title">English</p>
                <div class="language_title_icon">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M12.005 6.358l2.89 2.209a.934.934 0 001.135-1.485L12.005 4 7.978 7.078a.937.937 0 101.137 1.489l2.89-2.209zm-.01 11.284l-2.89-2.208a.934.934 0 00-1.135 1.484L11.995 20l4.028-3.078a.937.937 0 10-1.138-1.489l-2.89 2.209z" fill="currentColor"></path></svg>
                </div>
            </div>
        </div>
        <div class="privacy">
            <div class="privacy_wrapper">
                <h1 class="privacy_title">Privacy</h1>
                <div class="privacy_item_info_title_switch_btn">
                    <div class="privacy_item_info_title_wrapper">
                        <p class="privacy_item_title">App usage analytics</p>
                        <p class="privacy_item_info">Help us improve our app experience by sharing anonymous statistics about how you use Zerion.</p>
                        <p class="privacy_item_info">We will not associate any of this to you and your personal data will not be sent to us.</p>
                    </div>
                    <div class="privacy_item_info_switch_btn">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
                <div class="privacy_item_info_title_switch_btn">
                    <div class="privacy_item_info_title_wrapper">
                        <p class="privacy_item_title">Support chat</p>
                        <p class="privacy_item_info">We use the Intercom Chat for easy communication with our DeFi users.</p>
                        <p class="privacy_item_info">To speak with a friendly Zerion team member, please activate the Support Chat here.</p>
                    </div>
                    <div class="privacy_item_info_switch_btn">
                        <label class="switch">
                            <input type="checkbox">
                            <span class="slider round"></span>
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="mobile_apps">
            <div class="mobile_apps_wrapper">
                <h1 class="mobile_apps_title">
                    Mobile Apps
                </h1>
                <div class="mobile_apps_links_wrapper">
                    <a href="" class="mobile_apps_link">
                        <div class="mobile_apps_link_img">
                            <img src="../images/mobile_link_img1.png" alt="">
                        </div>
                    </a>
                    <a href="" class="mobile_apps_link">
                        <div class="mobile_apps_link_img">
                            <img src="../images/mobile_link_img2.png" alt="">
                        </div>
                    </a>
                </div>
            </div>
        </div>
        <div class="partners">
            <div class="partners_wrapper">
                <h1 class="partners_title">Partners</h1>
                <div class="partners_links_wrapper">
                    <a href="" class="partners_link">
                        <div class="partners_link_img">
                            <svg width="160" height="23" viewBox="0 0 160 23" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11.479.4A15.315 15.315 0 0022.47 4.248c.318 1.22.487 2.499.487 3.818 0 7.08-4.877 13.03-11.478 14.716C4.877 21.096 0 15.147 0 8.067a15.118 15.118 0 01.487-3.819A15.315 15.315 0 0011.48.4z" fill="url(#alchemy_badge_svg__paint0_linear_104:2659)"></path><path d="M6.56 12.57l2.81 2.822 7.028-7.054" stroke="#fff" stroke-width="1.767" stroke-linecap="round" stroke-linejoin="round"></path><path d="M38.288 5.433L35.368.55a.167.167 0 00-.283-.002l-.875 1.463a.307.307 0 000 .315l1.904 3.186a.336.336 0 00.283.158h1.75a.168.168 0 00.14-.08.154.154 0 000-.157zM30.632 7.985l2.92-4.883a.16.16 0 01.06-.057.168.168 0 01.223.057l.876 1.462a.308.308 0 010 .316l-1.905 3.185a.32.32 0 01-.12.116.335.335 0 01-.162.042h-1.75a.168.168 0 01-.142-.08.153.153 0 010-.158zM33.835 8.22h5.84a.168.168 0 00.082-.02.16.16 0 00.082-.137.155.155 0 00-.022-.079l-.874-1.462a.32.32 0 00-.12-.116.336.336 0 00-.163-.042h-3.808a.336.336 0 00-.164.042.321.321 0 00-.12.116l-.874 1.462a.153.153 0 000 .158.161.161 0 00.06.058.168.168 0 00.08.02zM44.75 5.263v-.11c0-.274-.096-.484-.288-.63-.186-.151-.43-.227-.731-.227-.232 0-.415.04-.549.118a1.26 1.26 0 00-.365.336.284.284 0 01-.114.092.444.444 0 01-.165.026h-.261a.261.261 0 01-.174-.068.188.188 0 01-.061-.16c.017-.167.101-.333.252-.495a1.75 1.75 0 01.619-.412c.255-.107.528-.16.818-.16.54 0 .975.146 1.306.437.337.292.505.7.505 1.228V7.8a.214.214 0 01-.07.16.23.23 0 01-.165.067h-.322a.23.23 0 01-.166-.067.215.215 0 01-.07-.16v-.344c-.092.201-.29.361-.592.479a2.477 2.477 0 01-.905.176c-.296 0-.56-.05-.792-.151a1.319 1.319 0 01-.54-.429 1.052 1.052 0 01-.183-.605c0-.42.163-.748.488-.983.325-.241.757-.404 1.297-.488l1.227-.193zm0 .672l-1.028.16c-.377.056-.67.148-.88.277-.208.124-.313.28-.313.471 0 .14.067.266.2.378.134.112.337.168.61.168.418 0 .758-.115 1.019-.344.26-.23.392-.53.392-.9v-.21zM47.605 7.801a.215.215 0 01-.07.16.23.23 0 01-.166.067h-.322a.23.23 0 01-.165-.067.214.214 0 01-.07-.16V2.287c0-.061.023-.115.07-.16a.23.23 0 01.165-.067h.322a.23.23 0 01.166.068c.046.044.07.098.07.16V7.8zM50.74 7.39c.58 0 .973-.22 1.176-.656a.528.528 0 01.122-.177.25.25 0 01.165-.05h.261a.23.23 0 01.166.067.18.18 0 01.07.143c0 .19-.079.395-.236.613a1.846 1.846 0 01-.679.555c-.296.152-.644.227-1.045.227-.411 0-.769-.084-1.07-.252a1.759 1.759 0 01-.697-.69 2.267 2.267 0 01-.27-.991 5.664 5.664 0 01-.009-.387c0-.134.004-.23.01-.285.052-.572.252-1.037.6-1.396.348-.358.827-.538 1.436-.538.401 0 .746.076 1.037.227.296.146.519.328.67.547.157.212.24.414.252.605a.179.179 0 01-.069.16.23.23 0 01-.166.067h-.26a.25.25 0 01-.166-.05.528.528 0 01-.122-.177c-.203-.437-.595-.656-1.176-.656-.319 0-.597.104-.836.311-.237.207-.374.521-.408.942a3.554 3.554 0 00-.01.302c0 .129.004.224.01.286.04.42.177.734.408.941.239.208.517.311.836.311zM57.764 7.801a.216.216 0 01-.07.16.23.23 0 01-.165.067h-.322a.23.23 0 01-.166-.067.215.215 0 01-.07-.16V5.582c0-.42-.107-.74-.321-.958-.215-.218-.514-.328-.897-.328-.377 0-.676.11-.897.328-.215.219-.322.538-.322.958v2.22a.214.214 0 01-.07.16.23.23 0 01-.165.066h-.322a.23.23 0 01-.166-.067.214.214 0 01-.07-.16V2.287c0-.061.024-.115.07-.16a.23.23 0 01.166-.067h.322a.23.23 0 01.165.068c.047.044.07.098.07.16v1.857c.128-.163.302-.297.522-.404.227-.112.503-.168.827-.168.366 0 .691.076.975.227.285.152.506.37.662.656.163.28.244.614.244 1v2.345zM60.815 3.573c.621 0 1.111.19 1.472.572.36.38.54.9.54 1.555v.227a.214.214 0 01-.07.16.23.23 0 01-.166.067h-2.995v.05c.012.37.128.661.349.874.226.208.516.311.87.311.29 0 .514-.036.67-.109.163-.073.308-.176.436-.31a.5.5 0 01.122-.093.412.412 0 01.156-.026h.262a.24.24 0 01.174.068.187.187 0 01.06.16c-.023.15-.11.308-.26.47-.146.157-.358.291-.636.404-.273.106-.6.16-.984.16-.371 0-.702-.082-.992-.245a1.919 1.919 0 01-.697-.689 2.399 2.399 0 01-.305-.983 6.143 6.143 0 01-.017-.387c0-.09.006-.218.017-.387.035-.347.137-.66.305-.941.174-.28.404-.502.688-.664.29-.163.624-.244 1.001-.244zm1.228 1.875v-.026c0-.341-.113-.613-.34-.815-.22-.207-.517-.311-.888-.311-.337 0-.624.104-.862.311-.232.207-.351.48-.357.815v.026h2.447zM64.669 4.145c.14-.174.296-.311.47-.412.174-.107.407-.16.697-.16.679 0 1.155.244 1.427.731.169-.235.36-.414.575-.537.215-.13.496-.194.845-.194.568 0 .99.165 1.262.496.279.33.418.799.418 1.404V7.8a.215.215 0 01-.07.16.229.229 0 01-.165.067h-.322a.23.23 0 01-.165-.067.215.215 0 01-.07-.16V5.557c0-.84-.343-1.261-1.027-1.261-.314 0-.563.1-.75.303-.185.201-.278.501-.278.9V7.8a.214.214 0 01-.07.16.23.23 0 01-.165.067h-.322a.23.23 0 01-.166-.067.214.214 0 01-.07-.16V5.557c0-.84-.342-1.261-1.027-1.261-.313 0-.563.1-.748.303-.186.201-.28.501-.28.9V7.8a.214.214 0 01-.069.16.23.23 0 01-.165.067h-.322a.23.23 0 01-.166-.067.214.214 0 01-.07-.16V3.884c0-.061.024-.115.07-.16a.23.23 0 01.166-.067h.322a.23.23 0 01.165.067c.047.045.07.099.07.16v.26zM72.802 9.424c-.053.134-.137.201-.253.201h-.357a.206.206 0 01-.148-.059.192.192 0 01-.06-.142c0-.023.002-.042.008-.06l.836-1.756-1.724-3.69a.175.175 0 01-.009-.059c0-.056.02-.104.06-.143a.206.206 0 01.149-.059h.357c.116 0 .2.068.252.202l1.341 2.858 1.359-2.858c.052-.134.136-.202.252-.202h.357a.205.205 0 01.209.202.19.19 0 01-.008.059l-2.621 5.506z" fill="#9C9FA8"></path><path d="M35.713 21.79c1.928 0 3.03-1.056 3.66-2.137l-1.6-.768a2.348 2.348 0 01-2.06 1.263c-1.588 0-2.742-1.211-2.742-2.852 0-1.64 1.154-2.852 2.742-2.852.905 0 1.692.56 2.06 1.263l1.6-.781c-.617-1.081-1.732-2.123-3.66-2.123-2.624 0-4.657 1.823-4.657 4.493 0 2.67 2.033 4.493 4.657 4.493zm7.445 0c.984 0 1.98-.3 2.61-.873l-.734-1.068c-.407.39-1.155.625-1.693.625-1.075 0-1.718-.664-1.823-1.459h4.71v-.364c0-2.058-1.286-3.465-3.175-3.465-1.929 0-3.28 1.472-3.28 3.295 0 2.019 1.456 3.308 3.385 3.308zm1.456-3.895h-3.122c.078-.625.524-1.393 1.56-1.393 1.103 0 1.523.794 1.562 1.393zm4.22 3.738v-4.155c.276-.403 1.01-.716 1.561-.716.184 0 .341.013.46.04v-1.616c-.787 0-1.575.456-2.02 1.016v-.86h-1.667v6.291h1.666zm4.925.156c.695 0 1.141-.182 1.39-.404l-.354-1.25c-.092.091-.328.183-.577.183-.368 0-.577-.3-.577-.69v-2.84h1.285v-1.446h-1.285v-1.719h-1.68v1.72h-1.049v1.445h1.05v3.282c0 1.12.63 1.72 1.797 1.72zm2.87-7.124a.993.993 0 00.998-.99.982.982 0 00-.997-.976c-.538 0-.997.43-.997.976 0 .547.46.99.997.99zm.84 6.968v-6.29h-1.666v6.29h1.666zm3.436 0v-4.845h1.286v-1.446h-1.286v-.338c0-.573.315-.886.788-.886a.94.94 0 01.537.156l.342-1.198c-.302-.156-.735-.26-1.207-.26-1.247 0-2.139.82-2.139 2.188v.338h-1.05v1.446h1.05v4.845h1.68zm3.07-6.968a.993.993 0 00.997-.99.982.982 0 00-.997-.976c-.538 0-.997.43-.997.976 0 .547.46.99.997.99zm.84 6.968v-6.29h-1.666v6.29h1.666zm4.37.156c.983 0 1.98-.3 2.61-.872l-.735-1.068c-.407.39-1.154.625-1.692.625-1.076 0-1.719-.664-1.824-1.459h4.71v-.364c0-2.058-1.286-3.465-3.175-3.465-1.928 0-3.28 1.472-3.28 3.295 0 2.019 1.457 3.308 3.385 3.308zm1.455-3.894h-3.122c.079-.625.525-1.393 1.561-1.393 1.102 0 1.522.794 1.561 1.393zm8.59 3.738v-8.687h-1.68v3.204a2.446 2.446 0 00-1.954-.964c-1.614 0-2.808 1.25-2.808 3.308 0 2.097 1.207 3.295 2.808 3.295.76 0 1.456-.338 1.954-.963v.807h1.68zm-3.123-1.315c-.944 0-1.6-.743-1.6-1.824 0-1.094.656-1.836 1.6-1.836.564 0 1.168.3 1.444.716v2.227c-.276.417-.88.717-1.444.717zm9.508 1.315v-8.687h-1.862v8.687h1.862zm7.379 0v-4.441c0-1.224-.67-2.006-2.06-2.006-1.036 0-1.81.495-2.217.977v-.82H87.05v6.29h1.666V17.4a1.808 1.808 0 011.443-.742c.708 0 1.168.3 1.168 1.172v3.803h1.665zm3.438 0v-4.845h1.285v-1.446h-1.285v-.338c0-.586.328-.886.787-.886.302 0 .525.091.695.26l.63-.976c-.407-.43-1.05-.586-1.653-.586-1.273 0-2.139.846-2.139 2.188v.338h-1.049v1.446h1.05v4.845h1.679zm3.649 0v-4.155c.275-.403 1.01-.716 1.561-.716.183 0 .341.013.459.04v-1.616c-.787 0-1.574.456-2.02 1.016v-.86h-1.666v6.291h1.666zm8.269 0V17.57c0-1.81-1.325-2.384-2.768-2.384-.997 0-1.994.313-2.768.99l.629 1.107c.538-.495 1.168-.742 1.85-.742.84 0 1.391.416 1.391 1.055v.846c-.42-.495-1.168-.768-2.007-.768-1.011 0-2.204.56-2.204 2.031 0 1.407 1.193 2.084 2.204 2.084.826 0 1.574-.3 2.007-.807v.651h1.666zm-3.004-.964c-.656 0-1.194-.338-1.194-.925 0-.612.538-.95 1.194-.95.537 0 1.062.182 1.338.547v.781c-.276.365-.801.547-1.338.547zm6.677 1.12c1.758 0 2.742-.872 2.742-2.018 0-2.54-3.752-1.759-3.752-2.696 0-.352.393-.625.997-.625.774 0 1.495.325 1.876.729l.669-1.133c-.63-.495-1.483-.86-2.558-.86-1.666 0-2.598.925-2.598 1.98 0 2.474 3.765 1.628 3.765 2.67 0 .39-.341.677-1.062.677-.788 0-1.732-.43-2.191-.86l-.722 1.16c.669.612 1.732.976 2.834.976zm5.831 0c.696 0 1.142-.182 1.391-.404l-.354-1.25c-.092.091-.328.183-.577.183-.368 0-.578-.3-.578-.69v-2.84h1.286v-1.446h-1.286v-1.719h-1.679v1.72h-1.049v1.445h1.049v3.282c0 1.12.63 1.72 1.797 1.72zm3.711-.156v-4.155c.276-.403 1.01-.716 1.561-.716.184 0 .342.013.46.04v-1.616c-.788 0-1.575.456-2.021 1.016v-.86h-1.666v6.291h1.666zm8.742 0v-6.29h-1.666V19.6c-.289.365-.8.717-1.443.717-.708 0-1.167-.287-1.167-1.16v-3.816h-1.667v4.468c0 1.224.656 1.98 2.047 1.98 1.036 0 1.797-.47 2.23-.951v.794h1.666zm4.343.156c1.299 0 2.086-.56 2.506-1.146l-1.089-1.003a1.603 1.603 0 01-1.338.678c-1.01 0-1.718-.743-1.718-1.837 0-1.094.708-1.823 1.718-1.823.577 0 1.036.234 1.338.677l1.089-1.016c-.42-.573-1.207-1.133-2.506-1.133-1.954 0-3.358 1.368-3.358 3.295 0 1.94 1.404 3.308 3.358 3.308zm5.458 0c.695 0 1.141-.182 1.391-.404l-.354-1.25c-.092.091-.328.183-.578.183-.367 0-.577-.3-.577-.69v-2.84h1.286v-1.446h-1.286v-1.719h-1.679v1.72h-1.05v1.445h1.05v3.282c0 1.12.63 1.72 1.797 1.72zm7.988-.156v-6.29h-1.666V19.6c-.289.365-.801.717-1.443.717-.709 0-1.168-.287-1.168-1.16v-3.816h-1.666v4.468c0 1.224.656 1.98 2.046 1.98 1.037 0 1.798-.47 2.231-.951v.794h1.666zm3.058 0v-4.155c.275-.403 1.01-.716 1.561-.716.184 0 .341.013.459.04v-1.616c-.787 0-1.574.456-2.02 1.016v-.86h-1.666v6.291h1.666zm5.776.156c.984 0 1.981-.3 2.611-.872l-.735-1.068c-.406.39-1.154.625-1.692.625-1.076 0-1.719-.664-1.823-1.459H160v-.364c0-2.058-1.286-3.465-3.175-3.465-1.928 0-3.279 1.472-3.279 3.295 0 2.019 1.456 3.308 3.384 3.308zm1.456-3.894h-3.122c.079-.625.525-1.393 1.561-1.393 1.102 0 1.522.794 1.561 1.393z" fill="currentColor"></path><defs><linearGradient id="alchemy_badge_svg__paint0_linear_104:2659" x1="30.724" y1="-9.274" x2="-1.253" y2="-7.811" gradientUnits="userSpaceOnUse"><stop stop-color="#05D5FF"></stop><stop offset="1" stop-color="#53F"></stop></linearGradient></defs></svg>
                        </div>
                    </a>
                </div>
                <div class="partners_info_wrapper">
                    <p class="partners_info">v1.56.10</p>
                </div>
            </div>
        </div>
        <div class="language_popup">
            <div class="language_popup_wrapper">
                <div class="language_hidden_div_title_icon_wrapper">
                    <h1 class="language_hidden_div_title">
                        Language
                    </h1>
                    <div class="language_hidden_div_close_icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="width: 24px;"><path fill-rule="evenodd" clip-rule="evenodd" d="M19.66 4.34a1.16 1.16 0 00-1.642 0l-6.02 6.02-6.016-6.017A1.16 1.16 0 104.34 5.984l6.017 6.018-6.017 6.017a1.16 1.16 0 001.641 1.642L12 13.643l6.02 6.02a1.16 1.16 0 001.641-1.642l-6.02-6.02 6.02-6.019a1.16 1.16 0 000-1.641z" fill="currentColor"></path></svg>
                    </div>
                </div>
                <button class="language_hidden_div_btn">
                    <div class="language_hidden_div_btn_names">
                        <p class="language_hidden_div_btn_english_name">English</p>
                        <p class="language_hidden_div_btn_russian_name">English</p>
                    </div>
                    <div class="language_hidden_div_btn_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary)"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                    </div>
                </button>
                <button class="language_hidden_div_btn">
                    <div class="language_hidden_div_btn_names">
                        <p class="language_hidden_div_btn_english_name">Русский</p>
                        <p class="language_hidden_div_btn_russian_name">Russian</p>
                    </div>
                    <div class="language_hidden_div_btn_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary)"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                    </div>
                </button>
                <button class="language_hidden_div_btn">
                    <div class="language_hidden_div_btn_names">
                        <p class="language_hidden_div_btn_english_name">中文</p>
                        <p class="language_hidden_div_btn_russian_name">Chinese</p>
                    </div>
                    <div class="language_hidden_div_btn_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary)"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                    </div>
                </button>
                <button class="language_hidden_div_btn">
                    <div class="language_hidden_div_btn_names">
                        <p class="language_hidden_div_btn_english_name">Français</p>
                        <p class="language_hidden_div_btn_russian_name">French</p>
                    </div>
                    <div class="language_hidden_div_btn_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary)"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                    </div>
                </button>
                <button class="language_hidden_div_btn">
                    <div class="language_hidden_div_btn_names">
                        <p class="language_hidden_div_btn_english_name">Deutsch</p>
                        <p class="language_hidden_div_btn_russian_name">German</p>
                    </div>
                    <div class="language_hidden_div_btn_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary)"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                    </div>
                </button>
                <button class="language_hidden_div_btn">
                    <div class="language_hidden_div_btn_names">
                        <p class="language_hidden_div_btn_english_name">Español</p>
                        <p class="language_hidden_div_btn_russian_name">Spanish</p>
                    </div>
                    <div class="language_hidden_div_btn_icon">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" color="var(--primary)"><path d="M9.714 18L4.7 12.946a1 1 0 010-1.409l.204-.205a1 1 0 011.418 0l3.393 3.409 7.962-8.023a1 1 0 011.422.002l.208.211a1 1 0 01-.003 1.406L9.714 18z" fill="currentColor"></path></svg>
                    </div>
                </button>
            </div>
        </div> -->
    </div>
</section>
@endsection