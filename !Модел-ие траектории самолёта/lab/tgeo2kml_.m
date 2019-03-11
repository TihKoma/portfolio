function outfile = tgeo2kml_(tgeo)

    outfile = 'flight_.kml';
    
    fhead = fopen('kmlhead_.txt');
    fbody = fopen('kmlbody_.txt');
    ftail = fopen('kmltail_.txt');
    fout = fopen(outfile, 'w');
    
    lon = tgeo(end,2);
    lat = tgeo(end,3);
    h = tgeo(end,4);
    
    while ~feof(fhead)
        fprintf(fout,fgets(fhead));
    end

    fprintf(fout,'\n<longitude> %f </longitude>', lon);
    fprintf(fout,'\n<latitude> %f </latitude>', lat);
    fprintf(fout,'\n<altitude> %f </altitude>', h);
    
    while ~feof(fbody)
        fprintf(fout,fgets(fbody));
    end
    
    fprintf(fout,'\n<coordinates>\n');
    for i = 1:size(tgeo,1)
        lon = tgeo(i,2);
        lat = tgeo(i,3);
        h = tgeo(i,4);
        fprintf(fout, '\t%f,%f,%f \n', lon, lat, h);

    end
    fprintf(fout,'\n</coordinates>');
    
    while ~feof(ftail)
        fprintf(fout,fgets(ftail));
    end
    
    fclose(fout); 

end