  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;
import java.util.ArrayList;

import android.content.Context;
import android.os.Bundle;
import android.os.Handler;
import android.os.Message;
import android.support.v4.app.DialogFragment;
import android.support.v4.app.FragmentManager;
import android.support.v4.app.FragmentTransaction;
import android.text.Editable;
import android.text.TextWatcher;
import android.view.Gravity;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.view.Window;
import android.view.WindowManager;
import android.widget.ArrayAdapter;
import android.widget.AutoCompleteTextView;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ProgressBar;
import android.widget.TextView;
import myrestaurant.main.R;


public class LoginFragment extends DialogFragment {
	
	LayoutInflater li;
	String table;
	AutoCompleteTextView spin = null;
	EditText etPass = null;
	ArrayAdapter<String> adapter = null;
	Button btnOk;
	Button btnCancel;
	Button btnDiners;
	ProgressBar pbar;
	String sid;
	
	NumberPicker adultsNb;
	NumberPicker childrenNb;
	
	View v;
	
	ArrayList<String> items = null;
	
	Emrpc rpc = null;
	
	EmPrefs emp = null;
	
	LoginFragment (String table){
		this.table = table;
	}
	
	@Override
	public void onCreate(Bundle savedInstanceState) {
		  super.onCreate(savedInstanceState);
		  li = (LayoutInflater) getActivity().getSystemService(Context.LAYOUT_INFLATER_SERVICE);
		  this.setCancelable(false);
		  emp = new EmPrefs(getActivity());
		  rpc = new Emrpc(getActivity());
	}
	
	@Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
            Bundle savedInstanceState) {
        v = li.inflate(R.layout.logindialog, container, false);
  
        WindowManager.LayoutParams WMLP = this.getDialog().getWindow().getAttributes();
        
        adultsNb =  (NumberPicker)v.findViewById(R.id.adultnumpick);
        childrenNb = (NumberPicker)v.findViewById(R.id.childrennumpick);
        etPass = (EditText)v.findViewById(R.id.loginPass);
        
        pbar = (ProgressBar)v.findViewById(R.id.pBarLogin); 
        
        btnOk = (Button)v.findViewById(R.id.lgnbtnOK);
        btnCancel = (Button)v.findViewById(R.id.lgnbtnCancel);
        btnDiners = (Button)v.findViewById(R.id.lgndinersbtnOK);
        
        btnDiners.setOnClickListener(new View.OnClickListener(){
        	@Override
			public void onClick(View v) {
        		
        		new Thread(new DinersRun()).start();
        	}
        });
        
        btnOk.setOnClickListener(new View.OnClickListener() {
        	@Override
			public void onClick(View v) {
     			pbar.setVisibility(View.VISIBLE);
        		new Thread(new NewSessionRun()).start();
        	}
        });
        
        btnCancel.setOnClickListener(new View.OnClickListener() {
			
			@Override
			public void onClick(View v) {
				
				dismiss();
			}
		});
        
        WMLP.y = 100;   //y position
        WMLP.gravity = Gravity.TOP;
        WMLP.windowAnimations = R.style.PauseDialogAnimation;
        this.getDialog().getWindow().setAttributes(WMLP);
        this.getDialog().requestWindowFeature(Window.FEATURE_NO_TITLE);
        //this.getDialog().getWindow().getAttributes().windowAnimations = R.style.PauseDialogAnimation;
        spin = (AutoCompleteTextView)v.findViewById(R.id.spinnerLogin);
        
        adapter = new ArrayAdapter<String>(getActivity(),
						R.layout.autocompleteview, items);
        
     	spin.setAdapter(adapter);
     	
     	spin.addTextChangedListener(new TextWatcher() {

     		public void onTextChanged(CharSequence s, int start, int before, int count) {
	     		if (count==1 && start == 0) {
	     			pbar.setVisibility(View.VISIBLE);
	     			new Thread(new LoadItemsRun()).start();
	     		}
     		}

     		public void beforeTextChanged(CharSequence s, int start, int count,
     		int after) {
     		// TODO Auto-generated method stub

     		}

     		public void afterTextChanged(Editable s) {

     		}
     	});
        
     	
        return v;
    }
	

    final Handler LoadItemsHandler = new Handler() {
  	  
        public void handleMessage(Message msg) {
        	
        	if(items!=null){
        		adapter = new ArrayAdapter<String>(getActivity(),
        				R.layout.autocompleteview, items);
        		spin.setAdapter(adapter);
        		adapter.notifyDataSetChanged();
        	}
        	pbar.setVisibility(View.GONE);
           
        };
    };
    
    // RUNNABLE
    private class LoadItemsRun implements Runnable{
    	
		@Override
		public void run() {
			
			items = rpc.getLoginNames() ;
			LoadItemsHandler.sendEmptyMessage(0);
		}
    	
    }
    
    final Handler NewSessionHandler = new Handler() {
    	  
        public void handleMessage(Message msg) {
        	if(msg.what==0)
        		pbar.setVisibility(View.GONE);
        	else if(msg.what==2){
        		getActivity().findViewById(R.id.btnConfig).setVisibility(View.VISIBLE);
        		pbar.setVisibility(View.GONE);
        		dismiss();
        	}
        	else{
        		((TextView)getActivity().findViewById(R.id.lblTable)).setText(table);
        		getActivity().findViewById(R.id.btnMenu).setEnabled(true);
        		getActivity().findViewById(R.id.btnDrinks).setEnabled(true);
        		getActivity().findViewById(R.id.btnWaiter).setEnabled(true);
        		getActivity().findViewById(R.id.btnOrder).setEnabled(true);
        		getActivity().findViewById(R.id.btnBill).setEnabled(true);
        		getActivity().findViewById(R.id.btnConfig).setVisibility(View.GONE);
        		
        		sid = msg.getData().getString("sid");
        		emp.setSid(sid);
        		emp.commit();
        		pbar.setVisibility(View.GONE);
        		if(emp.getValue("restmode").equals(new String("allyoucaneat"))){
        			v.findViewById(R.id.tableLayout2).setVisibility(View.VISIBLE);
        			v.findViewById(R.id.tableLayout1).setVisibility(View.GONE);
        		}
        		else{
        			dismiss();
        			FragmentManager fm = getActivity().getSupportFragmentManager();
                	FragmentTransaction ft ;
    	    		if(fm.findFragmentByTag("rightfragment")!=null){
    	    			ft = fm.beginTransaction();
    	    			ft.setCustomAnimations(android.R.anim.fade_in,
    		                    android.R.anim.fade_out);
    	    			ft.remove(fm.findFragmentByTag("rightfragment"));
    	    			ft.commit();
    	    		}
        		}
        	}
        };
    };
    
    // RUNNABLE
    private class NewSessionRun implements Runnable{
    	
		@Override
		public void run() {
	
			Message msg = new Message();
			Bundle b = new Bundle();
			
			//Emrpc rpc = new Emrpc(getActivity());
			
			if(spin.getText().toString().equals(new String("admin"))){
		//		if(etPass.getText().toString().equalsIgnoreCase(new String(emp.getValue("password")))){
					msg.what=2;
					NewSessionHandler.sendMessage(msg);
//				}
//				else{
//					msg.what=0;
//					NewSessionHandler.sendMessage(msg);
//				}
			return;
			}
			
    		if(rpc.newSession(spin.getText().toString(),etPass.getText().toString(), table)){
    			b.putString("sid", (String)rpc.getData());
    			msg.what = 1;
    			msg.setData(b);
    			NewSessionHandler.sendMessage(msg);
    		}
    		else{
    			msg.what = 0;
    			NewSessionHandler.sendMessage(msg);
    		}
		}
    	
    }

    final Handler DinersHandler = new Handler() {
  	  
        public void handleMessage(Message msg) {
        	if(msg.what==1){
        		dismiss();
        		FragmentManager fm = getActivity().getSupportFragmentManager();
            	FragmentTransaction ft ;
	    		if(fm.findFragmentByTag("rightfragment")!=null){
	    			ft = fm.beginTransaction();
	    			ft.setCustomAnimations(android.R.anim.fade_in,
		                    android.R.anim.fade_out);
	    			ft.remove(fm.findFragmentByTag("rightfragment"));
	    			ft.commit();
	    		}
        	}
        }
    };
    
 // RUNNABLE
    private class DinersRun implements Runnable{
    	
		@Override
		public void run() {
			
			if (rpc.setDinersNumber(sid, Integer.toString(adultsNb.getValue()) , Integer.toString(childrenNb.getValue()) )){
				DinersHandler.sendEmptyMessage(1);
			}
			else
			{
				DinersHandler.sendEmptyMessage(0);
			}
		}
    }
}
