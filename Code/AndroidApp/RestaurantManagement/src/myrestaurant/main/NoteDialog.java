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
import android.view.inputmethod.InputMethodManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.TextView;
import myrestaurant.main.R;

public class NoteDialog extends DialogFragment {
	
	private LayoutInflater li = null;
	private String note;
	
	private Button btnNoteOk;
	private Button btnNoteCancel;
	
	private EditText etNote;
	
	private ItemFragment itemFrag;
	
	NoteDialog(ItemFragment itemFrag,String note){
		this.itemFrag = itemFrag;
		this.note = note;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		  super.onCreate(savedInstanceState);
		  li = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
	}
	
	@Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
            Bundle savedInstanceState) {
		
        View v = li.inflate(R.layout.notedialog, container, false);
       
        WindowManager.LayoutParams WMLP = this.getDialog().getWindow().getAttributes();
        WMLP.y = 100;   //y position
        WMLP.gravity = Gravity.TOP;
        WMLP.windowAnimations = R.style.PauseDialogAnimation;
        this.getDialog().getWindow().setAttributes(WMLP);
        this.getDialog().requestWindowFeature(Window.FEATURE_NO_TITLE);
        
        etNote = (EditText)v.findViewById(R.id.txtNoteDlg);
        
        btnNoteOk = (Button)v.findViewById(R.id.btnNoteOK);
        btnNoteOk.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				itemFrag.setNote(etNote.getText().toString());
				InputMethodManager imm = (InputMethodManager)getActivity().getSystemService(
					      Context.INPUT_METHOD_SERVICE);
					imm.hideSoftInputFromWindow(etNote.getWindowToken(), 0);
					dismiss();
			}
        	
        });
        
        btnNoteCancel = (Button)v.findViewById(R.id.btnNoteCancel);
        btnNoteCancel.setOnClickListener(new View.OnClickListener() {

			@Override
			public void onClick(View v) {
				InputMethodManager imm = (InputMethodManager)getActivity().getSystemService(
					      Context.INPUT_METHOD_SERVICE);
					imm.hideSoftInputFromWindow(etNote.getWindowToken(), 0);
					dismiss();
			}
        	
        });
        
        etNote.setText(note);
        
        return v;
	}

}
