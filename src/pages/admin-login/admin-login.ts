import { Component } from '@angular/core';
import { TranslateService } from '@ngx-translate/core';
import { IonicPage, NavController, ToastController } from 'ionic-angular';

import { User } from '../../providers';
import { MainPage } from '../';
import { AdminProfilePage } from '../admin-profile/admin-profile';

@IonicPage()
@Component({
  selector: 'page-admin-login',
  templateUrl: 'admin-login.html'
})
export class AdminLoginPage {
  // The account fields for the login form.
  // If you're using the username field with or without email, make
  // sure to add it to the type
  account: { email: string, password: string } = {
    email: 'test@example.com',
    password: 'test'
  };

  // Our translated text strings
  private loginErrorString: string;

  constructor(public navCtrl: NavController,
    public user: User,
    public toastCtrl: ToastController,
    public translateService: TranslateService) {

    this.translateService.get('LOGIN_ERROR').subscribe((value) => {
      this.loginErrorString = value;
    })
  }
// login as admin
  adminLogin() {
    this.user.login(this.account).subscribe((resp) => {
      this.navCtrl.push(AdminProfilePage);
    }, (err) => {
      this.navCtrl.push(AdminProfilePage);
      // unable to log in 
      let toast = this.toastCtrl.create({
        message: this.loginErrorString,
        duration: 3000, 
        position: 'top'
      });
      toast.present();
    });
  }
}