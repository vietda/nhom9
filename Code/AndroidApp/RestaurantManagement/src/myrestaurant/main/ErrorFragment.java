  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import android.content.Context;
import android.os.Bundle;
import android.support.v4.app.DialogFragment;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.TextView;
import myrestaurant.main.R;

public class ErrorFragment extends DialogFragment {
	
	TextView txtErr = null;
	Button btnOk = null;
	LayoutInflater li = null;
	
	String err = null;
	
	ErrorFragment(String error){
		err = error;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		  super.onCreate(savedInstanceState);
		  li = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		  //this.setCancelable(false);
	}
	
	@Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
            Bundle savedInstanceState) {
        View v = li.inflate(R.layout.errordialog, container, false);
        ((TextView)v.findViewById(R.id.txtErrDlg)).setText(err);
        
        WindowManager.LayoutParams WMLP = this.getDialog().getWindow().getAttributes();
        WMLP.y = 100;   //y position
        WMLP.gravity = Gravity.TOP;
        WMLP.windowAnimations = R.style.PauseDialogAnimation;
        this.getDialog().getWindow().setAttributes(WMLP);
        this.getDialog().requestWindowFeature(Window.FEATURE_NO_TITLE);
        
        btnOk = (Button)v.findViewById(R.id.btnErrDlg);
        
        btnOk.setOnClickListener(new View.OnClickListener() {
			
				@Override
				public void onClick(View v) {
					dismiss();
				}
			
			});
        
        return v;
	}
}
