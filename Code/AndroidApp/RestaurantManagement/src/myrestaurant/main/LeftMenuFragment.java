  // written by: All members
  // tested by: All members
  // debugged by: All members

package myrestaurant.main;

import android.os.Bundle;
import android.support.v4.app.Fragment;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import myrestaurant.main.R;

public class LeftMenuFragment extends Fragment{

	 @Override
	    public View onCreateView(LayoutInflater inflater, ViewGroup container, Bundle savedInstanceState) {
	        // Inflate the layout for this fragment
	        return inflater.inflate(R.layout.leftmenu, null, true);
	    }
	 
}
