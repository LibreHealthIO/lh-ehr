/*
 * ===========================================
 * Java Pdf Extraction Decoding Access Library
 * ===========================================
 *
 * Project Info: http://www.idrsolutions.com
 * Help section for developers at http://www.idrsolutions.com/java-pdf-library-support/
 *
 * (C) Copyright 1997-2014, IDRsolutions and Contributors.
 *
 * This file is part of JPedal
 *
 @LICENSE@
 *
 * ---------------
 * EcmaParser.js
 * ---------------
 */

var EcmaFilter = {
    decode: function (name, data) {
        if (name === "FlateDecode") {
            var ef = new EcmaFlate();
            var input = [];
            var a = 0;
            for (var i = 2, ii = data.length; i < ii; i++) {
                input[a++] = data[i];
            }
            return ef.decode(input);
        } else if (name === "ASCII85Decode") {
            var as8 = new EcmaAscii85();
            return as8.decode(data);
        } else if (name === "ASCIIHexDecode") {
            var ash = new EcmaAsciiHex();
            return ash.decode(data);
        } else if (name === "RunLengthDecode") {
            var rlc = new EcmaRunLength();
            return rlc.decode();
        } else {
            console.log("This type of decoding is not supported yet : " + name);
            return data;
        }
    },
    applyPredictor: function (data, mainPred, bos, colors, bpc, columns, earlychange) {
        if (mainPred === 1) {
            return data;
        }
        var predictor;
        var bytesAvailable = data.length;
        var bis = new EcmaBuffer(data);

        var bpp = (colors * bpc + 7) >> 3;
        var rowLength = ((columns * colors * bpc + 7) >> 3) + bpp;

        var thisLine = this.createByteBuffer(rowLength);
        var lastLine = this.createByteBuffer(rowLength);
        var curPred;
        var count = 0;
        var byteCount = 0;
        while (true) {
            if (bytesAvailable <= byteCount) {
                break;
            }
            predictor = mainPred;
            var i = 0;
            var offset = bpp;
            if (predictor >= 10) {
                curPred = bis.getByte();
                if (curPred === -1) {
                    break;
                }
                curPred += 10;
            } else {
                curPred = predictor;
            }
            while (offset < rowLength) {
                i = bis.readTo(thisLine, offset, rowLength - offset);
                if (i === -1) {
                    break;
                }
                offset += i;
                byteCount += i;
            }
            if (i === -1) {
                break;
            }
            switch (curPred) {
                case 2:
                    for (var i1 = bpp; i1 < rowLength; i1++) {
                        var sub = thisLine[i1] & 0xff;
                        var raw = thisLine[i1 - bpp] & 0xff;
                        thisLine[i1] = (sub + raw) & 0xff;
                        if (bos) {
                            bos[count] = thisLine[i1];
                        }
                        count++;
                    }
                    break;
                case 10:
                    for (var i1 = bpp; i1 < rowLength; i1++) {
                        if (bos) {
                            bos[count] = thisLine[i1] & 0xff;
                        }
                        count++;
                    }
                    break;
                case 11:
                    for (var i1 = bpp; i1 < rowLength; i1++) {
                        var sub = thisLine[i1] & 0xff;
                        var raw = thisLine[i1 - bpp] & 0xff;
                        thisLine[i1] = sub + raw;
                        if (bos) {
                            bos[count] = thisLine[i1] & 0xff;
                        }
                        count++;
                    }
                    break;
                case 12:
                    for (var i1 = bpp; i1 < rowLength; i1++) {
                        var sub = (lastLine[i1] & 0xff) + (thisLine[i1] & 0xff);
                        thisLine[i1] = sub;
                        if (bos) {
                            bos[count] = thisLine[i1] & 0xff;
                        }
                        count++;
                    }
                    break;
                case 13:
                    for (var i1 = bpp; i1 < rowLength; i1++) {
                        var av = thisLine[i1] & 0xff;
                        var ff = ((thisLine[i1 - bpp] & 0xff) + (lastLine[i1] & 0xff) >> 1);
                        thisLine[i1] = av + ff;
                        if (bos) {
                            bos[count] = thisLine[i1] & 0xff;
                        }
                        count++;
                    }
                    break;
                case 14:
                    for (var i1 = bpp; i1 < rowLength; i1++) {
                        var a = thisLine[i1 - bpp] & 0xff;
                        var b = lastLine[i1] & 0xff;
                        var c = lastLine[i1 - bpp] & 0xff;
                        var p = a + b - c;
                        var pa = p - a, pb = p - b, pc = p - c;
                        pa = pa < 0 ? -pa : pa;
                        pb = pb < 0 ? -pb : pb;
                        pc = pc < 0 ? -pc : pc;
                        if (pa <= pb && pa <= pc) {
                            thisLine[i1] = thisLine[i1] + a;
                        } else if (pb <= pc) {
                            thisLine[i1] = thisLine[i1] + b;
                        } else {
                            thisLine[i1] = thisLine[i1] + c;
                        }
                        if (bos) {
                            bos[count] = thisLine[i1] & 0xff;
                        }
                        count++;
                    }
                    break;
                case 15:
                    break;
            }
            for (var i = 0; i < rowLength; i++) {
                lastLine[i] = thisLine[i];
            }
        }
        return count;
    },
    createByteBuffer: function (size) {
        var arr = [];
        for (var i = 0; i < size; i++) {
            arr.push(0);
        }
        return arr;
    },
    decodeBase64: function (bStr) {
        var hashKey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var enc1, enc2, enc3, enc4;
        var output = [];
        var input = bStr.replace(/[^A-Za-z0-9\+\/\=]/g, "");
        var ii = input.length;
        var i = 0;
        while (i < ii) {
            enc1 = hashKey.indexOf(input.charAt(i++));
            enc2 = hashKey.indexOf(input.charAt(i++));
            enc3 = hashKey.indexOf(input.charAt(i++));
            enc4 = hashKey.indexOf(input.charAt(i++));
            output.push((enc1 << 2) | (enc2 >> 4));
            if (enc3 !== 64) {
                output.push(((enc2 & 15) << 4) | (enc3 >> 2));
            }
            if (enc4 !== 64) {
                output.push(((enc3 & 3) << 6) | enc4);
            }
        }
        return output;
    },
    encodeBase64: function (inputArr) { //input is byte array
        var hashKey = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
        var output = "";
        var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
        var i = 0;
        var iLen = inputArr.length;
        while (i < iLen) {
            chr1 = inputArr[i++];
            chr2 = inputArr[i++];
            chr3 = inputArr[i++];
            enc1 = chr1 >> 2;
            enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
            enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
            enc4 = chr3 & 63;
            if (isNaN(chr2)) {
                enc3 = enc4 = 64;
            } else if (isNaN(chr3)) {
                enc4 = 64;
            }
            output += hashKey.charAt(enc1) + hashKey.charAt(enc2) +
                    hashKey.charAt(enc3) + hashKey.charAt(enc4);
        }
        return output;
    }
};

function EcmaFlate() {
    this.decode = function (data) {
        var res, buff;
        var k, j, buffLen = 1024;
        zip_wp = 0;
        bitsBuffer = 0;
        bitsLength = 0;
        flateType = -1;
        isEOF = false;
        zip_copy_leng = zip_copy_dist = 0;
        zip_tl = null;

        flateData = data;
        flatePos = 0;

        buff = new Array(buffLen);
        res = [];
        while ((k = inflateChunks(buff, 0, buffLen)) > 0) {
            for (j = 0; j < k; j++) {
                res.push(buff[j]);
            }
        }
        flateData = null;
        return res;
    };

    var MASK_BITS = [
        0x0000, 0x0001, 0x0003, 0x0007, 0x000f, 0x001f, 0x003f, 0x007f, 0x00ff,
        0x01ff, 0x03ff, 0x07ff, 0x0fff, 0x1fff, 0x3fff, 0x7fff, 0xffff
    ];
    var CODE_LENGTHS = [
        3, 4, 5, 6, 7, 8, 9, 10, 11, 13, 15, 17, 19, 23, 27, 31, 35, 43, 51, 59,
        67, 83, 99, 115, 131, 163, 195, 227, 258, 0, 0
    ];
    var CODE_DISTANCES = [
        1, 2, 3, 4, 5, 7, 9, 13, 17, 25, 33, 49, 65, 97, 129, 193, 257, 385, 513,
        769, 1025, 1537, 2049, 3073, 4097, 6145, 8193, 12289, 16385, 24577
    ];
    var FLATE_MARGINS = [
        16, 17, 18, 0, 8, 7, 9, 6, 10, 5, 11, 4, 12, 3, 13, 2, 14, 1, 15
    ];
    var zip_cplext = [
        0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 1, 1, 2, 2, 2, 2, 3, 3, 3, 3, 4, 4, 4, 4,
        5, 5, 5, 5, 0, 99, 99
    ];
    var zip_cpdext = [
        0, 0, 0, 0, 1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6, 7, 7, 8, 8, 9, 9, 10,
        10, 11, 11, 12, 12, 13, 13
    ];

    var WINDOW_SIZE = 32768;
    var STORED_BLOCK = 0;
    var windowSlides = new Array(WINDOW_SIZE << 1);
    var flateType;
    var flateData;
    var flatePos;
    var bitsBuffer;
    var bitsLength;
    var isEOF;
    var zip_wp;
    var zip_fixed_tl = null;
    var zip_fixed_td, zip_fixed_bl, zip_fixed_bd;
    var zip_copy_leng;
    var zip_copy_dist;
    var zip_lbits = 9;
    var zip_dbits = 6;
    var zip_tl, zip_td;
    var zip_bl, zip_bd;

    function readByte() {
        if (flateData.length === flatePos) {
            return -1;
        }
        return flateData[flatePos++] & 0xff;
    }

    function readBits(x) {
        return bitsBuffer & MASK_BITS[x];
    }

    function HuffmanTableList() {
        this.next = null;
        this.list = null;
    }

    function HuffmanTableNode() {
        this.e = 0;
        this.b = 0;
        this.n = 0;
        this.t = null;
    }

    function HuffmanTableBlock(b, n, s, d, e, mm) {
        this.BMAX = 16;
        this.N_MAX = 288;
        this.status = 0;
        this.root = null;
        this.m = 0;
        {
            var a, c = new Array(this.BMAX + 1);
            var el, f, g, h, i, j, k, lx = new Array(this.BMAX + 1);
            var p, pidx, q;
            var r = new HuffmanTableNode();
            var u = new Array(this.BMAX);
            var v = new Array(this.N_MAX);
            var x = new Array(this.BMAX + 1);
            var w, xp, y, z, o, tail;

            tail = this.root = null;
            for (i = 0; i < c.length; i++) {
                c[i] = 0;
            }
            for (i = 0; i < lx.length; i++) {
                lx[i] = 0;
            }
            for (i = 0; i < u.length; i++) {
                u[i] = null;
            }
            for (i = 0; i < v.length; i++) {
                v[i] = 0;
            }
            for (i = 0; i < x.length; i++) {
                x[i] = 0;
            }

            el = n > 256 ? b[256] : this.BMAX;
            p = b;
            pidx = 0;
            i = n;
            do {
                c[p[pidx]]++;
                pidx++;
            } while (--i > 0);
            if (c[0] === n) {
                this.root = null;
                this.m = 0;
                this.status = 0;
                return;
            }
            for (j = 1; j <= this.BMAX; j++) {
                if (c[j] !== 0) {
                    break;
                }
            }
            k = j;
            if (mm < j) {
                mm = j;
            }
            for (i = this.BMAX; i !== 0; i--) {
                if (c[i] !== 0) {
                    break;
                }
            }
            g = i;
            if (mm > i) {
                mm = i;
            }
            for (y = 1 << j; j < i; j++, y <<= 1) {
                if ((y -= c[j]) < 0) {
                    this.status = 2;
                    this.m = mm;
                    return;
                }
            }
            if ((y -= c[i]) < 0) {
                this.status = 2;
                this.m = mm;
                return;
            }
            c[i] += y;

            x[1] = j = 0;
            p = c;
            pidx = 1;
            xp = 2;
            while (--i > 0) {
                x[xp++] = (j += p[pidx++]);
            }

            p = b;
            pidx = 0;
            i = 0;
            do {
                if ((j = p[pidx++]) !== 0) {
                    v[x[j]++] = i;
                }
            } while (++i < n);
            n = x[g];
            x[0] = i = 0;
            p = v;
            pidx = 0;
            h = -1;
            w = lx[0] = 0;
            q = null;
            z = 0;

            for (; k <= g; k++) {
                a = c[k];
                while (a-- > 0) {
                    while (k > w + lx[1 + h]) {
                        w += lx[1 + h];
                        h++;
                        z = (z = g - w) > mm ? mm : z;
                        if ((f = 1 << (j = k - w)) > a + 1) {
                            f -= a + 1;
                            xp = k;
                            while (++j < z) {
                                if ((f <<= 1) <= c[++xp])
                                    break;
                                f -= c[xp];
                            }
                        }
                        if (w + j > el && w < el)
                            j = el - w;
                        z = 1 << j;
                        lx[1 + h] = j;

                        q = new Array(z);
                        for (o = 0; o < z; o++) {
                            q[o] = new HuffmanTableNode();
                        }

                        if (tail) {
                            tail = tail.next = new HuffmanTableList();
                        } else {
                            tail = this.root = new HuffmanTableList();
                        }
                        tail.next = null;
                        tail.list = q;
                        u[h] = q;
                        if (h > 0) {
                            x[h] = i;
                            r.b = lx[h];
                            r.e = 16 + j;
                            r.t = q;
                            j = (i & ((1 << w) - 1)) >> (w - lx[h]);
                            u[h - 1][j].e = r.e;
                            u[h - 1][j].b = r.b;
                            u[h - 1][j].n = r.n;
                            u[h - 1][j].t = r.t;
                        }
                    }

                    r.b = k - w;
                    if (pidx >= n) {
                        r.e = 99;
                    } else if (p[pidx] < s) {
                        r.e = (p[pidx] < 256 ? 16 : 15);
                        r.n = p[pidx++];
                    } else {
                        r.e = e[p[pidx] - s];
                        r.n = d[p[pidx++] - s];
                    }

                    f = 1 << (k - w);
                    for (j = i >> w; j < z; j += f) {
                        q[j].e = r.e;
                        q[j].b = r.b;
                        q[j].n = r.n;
                        q[j].t = r.t;
                    }

                    for (j = 1 << (k - 1); (i & j) !== 0; j >>= 1) {
                        i ^= j;
                    }
                    i ^= j;

                    while ((i & ((1 << w) - 1)) !== x[h]) {
                        w -= lx[h];
                        h--;
                    }
                }
            }

            this.m = lx[1];
            this.status = ((y !== 0 && g !== 1) ? 1 : 0);
        }
    }

    function zip_NEEDBITS(n) {
        while (bitsLength < n) {
            bitsBuffer |= readByte() << bitsLength;
            bitsLength += 8;
        }
    }

    function ignoreBits(n) {
        bitsBuffer >>= n;
        bitsLength -= n;
    }

    function decodeHFC(buff, off, size) {

        if (size === 0) {
            return 0;
        }
        var e, t, n = 0;
        while (true) {
            zip_NEEDBITS(zip_bl);
            t = zip_tl.list[readBits(zip_bl)];
            e = t.e;
            while (e > 16) {
                if (e === 99) {
                    return -1;
                }
                ignoreBits(t.b);
                e -= 16;
                zip_NEEDBITS(e);
                t = t.t[readBits(e)];
                e = t.e;
            }
            ignoreBits(t.b);

            if (e === 16) {
                zip_wp &= WINDOW_SIZE - 1;
                buff[off + n++] = windowSlides[zip_wp++] = t.n;
                if (n === size) {
                    return size;
                }
                continue;
            }

            if (e === 15) {
                break;
            }
            zip_NEEDBITS(e);
            zip_copy_leng = t.n + readBits(e);
            ignoreBits(e);

            zip_NEEDBITS(zip_bd);
            t = zip_td.list[readBits(zip_bd)];
            e = t.e;

            while (e > 16) {
                if (e === 99) {
                    return -1;
                }
                ignoreBits(t.b);
                e -= 16;
                zip_NEEDBITS(e);
                t = t.t[readBits(e)];
                e = t.e;
            }
            ignoreBits(t.b);
            zip_NEEDBITS(e);
            zip_copy_dist = zip_wp - t.n - readBits(e);
            ignoreBits(e);

            while (zip_copy_leng > 0 && n < size) {
                zip_copy_leng--;
                zip_copy_dist &= WINDOW_SIZE - 1;
                zip_wp &= WINDOW_SIZE - 1;
                buff[off + n++] = windowSlides[zip_wp++] = windowSlides[zip_copy_dist++];
            }
            if (n === size) {
                return size;
            }
        }

        flateType = -1;
        return n;
    }

    function decodeHFCS(buff, off, size) {
        var n;
        n = bitsLength & 7;
        ignoreBits(n);
        zip_NEEDBITS(16);
        n = readBits(16);
        ignoreBits(16);
        zip_NEEDBITS(16);
        if (n !== ((~bitsBuffer) & 0xffff))
            return -1;
        ignoreBits(16);

        zip_copy_leng = n;

        n = 0;
        while (zip_copy_leng > 0 && n < size) {
            zip_copy_leng--;
            zip_wp &= WINDOW_SIZE - 1;
            zip_NEEDBITS(8);
            buff[off + n++] = windowSlides[zip_wp++] = readBits(8);
            ignoreBits(8);
        }

        if (zip_copy_leng === 0) {
            flateType = -1;
        }
        return n;
    }

    function decodeHFCF(buff, off, size) {
        if (zip_fixed_tl == null) {
            var i;
            var l = new Array(288);
            var h;

            for (i = 0; i < 144; i++) {
                l[i] = 8;
            }
            for (; i < 256; i++) {
                l[i] = 9;
            }
            for (; i < 280; i++) {
                l[i] = 7;
            }
            for (; i < 288; i++) {
                l[i] = 8;
            }
            zip_fixed_bl = 7;

            h = new HuffmanTableBlock(l, 288, 257, CODE_LENGTHS, zip_cplext, zip_fixed_bl);
            if (h.status !== 0) {
                throw("EcmaFlateDecodeError : Huffman Status " + h.status);
                return -1;
            }
            zip_fixed_tl = h.root;
            zip_fixed_bl = h.m;

            for (i = 0; i < 30; i++) {
                l[i] = 5;
            }
            zip_fixed_bd = 5;

            h = new HuffmanTableBlock(l, 30, 0, CODE_DISTANCES, zip_cpdext, zip_fixed_bd);
            if (h.status > 1) {
                zip_fixed_tl = null;
                throw("EcmaFlateDecodeError : Huffman Status" + h.status);
                return -1;
            }
            zip_fixed_td = h.root;
            zip_fixed_bd = h.m;
        }

        zip_tl = zip_fixed_tl;
        zip_td = zip_fixed_td;
        zip_bl = zip_fixed_bl;
        zip_bd = zip_fixed_bd;
        return decodeHFC(buff, off, size);
    }

    function decodeHFCD(buff, off, size) {
        var i, j, l, n, t, nb, nl, nd, h;
        var ll = new Array(286 + 30);

        for (i = 0; i < ll.length; i++) {
            ll[i] = 0;
        }

        zip_NEEDBITS(5);
        nl = 257 + readBits(5);
        ignoreBits(5);
        zip_NEEDBITS(5);
        nd = 1 + readBits(5);
        ignoreBits(5);
        zip_NEEDBITS(4);
        nb = 4 + readBits(4);
        ignoreBits(4);
        if (nl > 286 || nd > 30) {
            return -1;
        }
        for (j = 0; j < nb; j++) {
            zip_NEEDBITS(3);
            ll[FLATE_MARGINS[j]] = readBits(3);
            ignoreBits(3);
        }
        for (; j < 19; j++) {
            ll[FLATE_MARGINS[j]] = 0;
        }

        zip_bl = 7;
        h = new HuffmanTableBlock(ll, 19, 19, null, null, zip_bl);
        if (h.status !== 0) {
            return -1;
        }
        zip_tl = h.root;
        zip_bl = h.m;
        n = nl + nd;
        i = l = 0;
        while (i < n) {
            zip_NEEDBITS(zip_bl);
            t = zip_tl.list[readBits(zip_bl)];
            j = t.b;
            ignoreBits(j);
            j = t.n;
            if (j < 16)
                ll[i++] = l = j;
            else if (j === 16) {
                zip_NEEDBITS(2);
                j = 3 + readBits(2);
                ignoreBits(2);
                if (i + j > n)
                    return -1;
                while (j-- > 0)
                    ll[i++] = l;
            } else if (j === 17) {
                zip_NEEDBITS(3);
                j = 3 + readBits(3);
                ignoreBits(3);
                if (i + j > n)
                    return -1;
                while (j-- > 0)
                    ll[i++] = 0;
                l = 0;
            } else {
                zip_NEEDBITS(7);
                j = 11 + readBits(7);
                ignoreBits(7);
                if (i + j > n)
                    return -1;
                while (j-- > 0)
                    ll[i++] = 0;
                l = 0;
            }
        }

        zip_bl = zip_lbits;
        h = new HuffmanTableBlock(ll, nl, 257, CODE_LENGTHS, zip_cplext, zip_bl);
        if (zip_bl === 0) {
            h.status = 1;
        }
        if (h.status !== 0) {
            return -1;
        }
        zip_tl = h.root;
        zip_bl = h.m;

        for (i = 0; i < nd; i++)
            ll[i] = ll[i + nl];
        zip_bd = zip_dbits;
        h = new HuffmanTableBlock(ll, nd, 0, CODE_DISTANCES, zip_cpdext, zip_bd);
        zip_td = h.root;
        zip_bd = h.m;

        if (zip_bd === 0 && nl > 257 || h.status !== 0) {
            return -1;
        }
        return decodeHFC(buff, off, size);
    }

    function inflateChunks(buff, off, size) {
        var n = 0, i;
        while (n < size) {
            if (isEOF && flateType === -1) {
                return n;
            }
            if (zip_copy_leng > 0) {
                if (flateType !== STORED_BLOCK) {
                    while (zip_copy_leng > 0 && n < size) {
                        zip_copy_leng--;
                        zip_copy_dist &= WINDOW_SIZE - 1;
                        zip_wp &= WINDOW_SIZE - 1;
                        buff[off + n++] = windowSlides[zip_wp++] =
                                windowSlides[zip_copy_dist++];
                    }
                } else {
                    while (zip_copy_leng > 0 && n < size) {
                        zip_copy_leng--;
                        zip_wp &= WINDOW_SIZE - 1;
                        zip_NEEDBITS(8);
                        buff[off + n++] = windowSlides[zip_wp++] = readBits(8);
                        ignoreBits(8);
                    }
                    if (zip_copy_leng === 0)
                        flateType = -1;
                }
                if (n === size)
                    return n;
            }

            if (flateType === -1) {
                if (isEOF) {
                    break;
                }
                zip_NEEDBITS(1);
                if (readBits(1) !== 0) {
                    isEOF = true;
                }
                ignoreBits(1);
                zip_NEEDBITS(2);
                flateType = readBits(2);
                ignoreBits(2);
                zip_tl = null;
                zip_copy_leng = 0;
            }
            switch (flateType) {
                case 0:
                    i = decodeHFCS(buff, off + n, size - n);
                    break;
                case 1:
                    if (zip_tl) {
                        i = decodeHFC(buff, off + n, size - n);
                    } else {
                        i = decodeHFCF(buff, off + n, size - n);
                    }
                    break;
                case 2:
                    if (zip_tl) {
                        i = decodeHFC(buff, off + n, size - n);
                    } else {
                        i = decodeHFCD(buff, off + n, size - n);
                    }
                    break;
                default:
                    i = -1;
                    break;
            }
            if (i === -1) {
                return (isEOF) ? 0 : -1;
            }
            n += i;
        }
        return n;
    }
}

function EcmaAsciiHex() {
    this.decode = function (data) {
        var res = [];
        var prefix = -1;
        var pointer = 0;
        var val, dd;
        var isEOF = false;
        for (var i = 0, ii = data.length; i < ii; i++) {
            val = data[i];
            if (val >= 0x30 && val <= 0x39) {
                dd = val & 0x0F;
            } else if ((val >= 0x41 && val <= 0x46) || (val >= 0x61 && val <= 0x66)) {
                dd = (val & 0x0f) + 9;
            } else if (val === 0x3e) {
                isEOF = true;
                break;
            } else {
                continue;
            }
            if (prefix < 0) {
                prefix = dd;
            } else {
                res[pointer++] = (prefix << 4) | dd;
                prefix = -1;
            }
        }
        if (prefix >= 0 && isEOF) {
            res[pointer++] = prefix << 4;
            prefix = -1;
        }
        return res;
    };
}

function EcmaAscii85() {
    this.decode = function (data) {
        var n = data.length, r = [], b = [0, 0, 0, 0, 0], j, t, x, w, d;
        for (var i = 0; i < n; ++i) {
            if (data[i] === 0x7a) {
                r.push(0, 0, 0, 0);
                continue;
            }
            for (j = 0; j < 5; ++j) {
                b[j] = data[i + j] - 0x21;
            }
            d = n - i;
            if (d < 5) {
                for (j = d; j < 4; b[++j] = 0) {
                    //
                }
                b[d] = 0x55;
            }
            t = (((b[0] * 85 + b[1]) * 85 + b[2]) * 85 + b[3]) * 85 + b[4];
            x = t & 0xff;
            t >>>= 8;
            w = t & 0xff;
            t >>>= 8;
            r.push(t >>> 8, t & 0xff, w, x);
            for (j = d; j < 5; ++j, r.pop()) {
                //
            }
            i += 4;
        }
        return r;

    };
}

function EcmaRunLength() {
    this.decode = function (data) {
        var len;
        var value;
        var count = data.length;
        var pp = 0;
        var res = [];

        for (var i = 0; i < count; i++) {
            len = data[i];
            if (len < 0) {
                len = 256 + len;
            }
            if (len === 128) {
                i = count;
            } else if (len > 128) {
                i++;
                len = 257 - len;
                value = data[i];
                for (var j = 0; j < len; j++) {
                    res[pp++] = value;
                }
            } else {
                i++;
                len++;
                for (var j = 0; j < len; j++) {
                    res[pp++] = data[i + j];
                }
                i = i + len - 1;
            }
        }
        return res;
    };
}

var EcmaKEY = {
    A: "A", AA: "AA", AC: "AC", AcroForm: "AcroForm", ActualText: "ActualText",
    AIS: "AIS", Alternate: "Alternate", AlternateSpace: "AlternateSpace",
    Annot: "Annot", Annots: "Annots", AntiAlias: "AntiAlias", AP: "AP",
    Array: "Array", ArtBox: "ArtBox", AS: "AS", Asset: "Asset", Assets: "Assets",
    Ascent: "Ascent", Author: "Author", AvgWidth: "AvgWidth", B: "B",
    BlackPoint: "BlackPoint", Background: "Background", Base: "Base",
    BaseEncoding: "BaseEncoding", BaseFont: "BaseFont", BaseState: "BaseState",
    BBox: "BBox", BC: "BC", BDC: "BDC", BG: "BG", BI: "BI",
    BitsPerComponent: "BitsPerComponent", BitsPerCoordinate: "BitsPerCoordinate",
    BitsPerFlag: "BitsPerFlag", BitsPerSample: "BitsPerSample", BlackIs1: "BlackIs1",
    BleedBox: "BleedBox", Blend: "Blend", Bounds: "Bounds", Border: "Border",
    BM: "BM", BPC: "BPC", BS: "BS", Btn: "Btn", ByteRange: "ByteRange", C: "C",
    C0: "C0", C1: "C1", C2: "C2", CA: "CA", ca: "ca", Calculate: "Calculate",
    CapHeight: "CapHeight", Caret: "Caret", Category: "Category", Catalog: "Catalog",
    Cert: "Cert", CF: "CF", CFM: "CFM", Ch: "Ch", CIDSet: "CIDSet",
    CIDSystemInfo: "CIDSystemInfo", CharProcs: "CharProcs", CharSet: "CharSet",
    CheckSum: "CheckSum", CIDFontType0C: "CIDFontType0C", CIDToGIDMap: "CIDToGIDMap",
    Circle: "Circle", ClassMap: "ClassMap", CMap: "CMap", CMapName: "CMapName",
    CMYK: "CMYK", CO: "CO", Color: "Color", Colors: "Colors", ColorBurn: "ColorBurn",
    ColorDodge: "ColorDodge", ColorSpace: "ColorSpace", ColorTransform: "ColorTransform",
    Columns: "Columns", Components: "Components", CompressedObject: "CompressedObject",
    Compatible: "Compatible", Configurations: "Configurations", Configs: "Configs",
    ContactInfo: "ContactInfo", Contents: "Contents", Coords: "Coords",
    Count: "Count", CreationDate: "CreationDate", Creator: "Creator",
    CropBox: "CropBox", CS: "CS", CVMRC: "CVMRC", D: "D", DA: "DA",
    DamageRowsBeforeError: "DamageRowsBeforeError", Darken: "Darken", DC: "DC",
    DCT: "DCT", Decode: "Decode", DecodeParms: "DecodeParms", Desc: "Desc",
    DescendantFonts: "DescendantFonts", Descent: "Descent", Dest: "Dest",
    Dests: "Dests", Difference: "Difference", Differences: "Differences",
    Domain: "Domain", DP: "DP", DR: "DR", DS: "DS", DV: "DV", DW: "DW", DW2: "DW2",
    E: "E", EarlyChange: "EarlyChange", EF: "EF", EFF: "EFF",
    EmbeddedFiles: "EmbeddedFiles", EOPROPtype: "EOPROPtype", Encode: "Encode",
    EncodeByteAlign: "EncodeByteAlign", Encoding: "Encoding", Encrypt: "Encrypt",
    EncryptMetadata: "EncryptMetadata", EndOfBlock: "EndOfBlock",
    EndOfLine: "EndOfLine", Exclusion: "Exclusion", Export: "Export",
    Extend: "Extend", Extends: "Extends", ExtGState: "ExtGState", Event: "Event",
    F: "F", FDF: "FDF", Ff: "Ff", Fields: "Fields", FIleAccess: "FIleAccess",
    FileAttachment: "FileAttachment", Filespec: "Filespec", Filter: "Filter",
    First: "First", FirstChar: "FirstChar", FIrstPage: "FIrstPage",
    Fit: "Fit", FItB: "FItB", FitBH: "FitBH", FItBV: "FItBV", FitH: "FitH",
    FItHeight: "FItHeight", FitR: "FitR", FitV: "FitV", FitWidth: "FitWidth",
    Flags: "Flags", Fo: "Fo", Font: "Font", FontBBox: "FontBBox",
    FontDescriptor: "FontDescriptor", FontFamily: "FontFamily", FontFile: "FontFile",
    FontFIle2: "FontFIle2", FontFile3: "FontFile3", FontMatrix: "FontMatrix",
    FontName: "FontName", FontStretch: "FontStretch", FontWeight: "FontWeight",
    Form: "Form", Format: "Format", FormType: "FormType", FreeText: "FreeText",
    FS: "FS", FT: "FT", FullScreen: "FullScreen", Function: "Function",
    Functions: "Functions", FunctionType: "FunctionType", G: "G", Gamma: "Gamma",
    GoBack: "GoBack", GoTo: "GoTo", GoToR: "GoToR", Group: "Group", H: "H",
    HardLight: "HardLight", Height: "Height", Hide: "Hide", Highlight: "Highlight",
    Hue: "Hue", Hival: "Hival", I: "I", ID: "ID", Identity: "Identity",
    Identity_H: "Identity_H", Identity_V: "Identity_V", IDTree: "IDTree", IM: "IM",
    Image: "Image", ImageMask: "ImageMask", Index: "Index", Indexed: "Indexed",
    Info: "Info", Ink: "Ink", InkList: "InkList", Instances: "Instances",
    Intent: "Intent", InvisibleRect: "InvisibleRect", IRT: "IRT", IT: "IT",
    ItalicAngle: "ItalicAngle", JavaScript: "JavaScript", JS: "JS", JT: "JT",
    JBIG2Globals: "JBIG2Globals", K: "K", Keywords: "Keywords", Keystroke: "Keystroke",
    Kids: "Kids", L: "L", Lang: "Lang", Last: "Last", LastChar: "LastChar",
    LastModified: "LastModified", LastPage: "LastPage", Launch: "Launch",
    Layer: "Layer", Leading: "Leading", Length: "Length", Length1: "Length1",
    Length2: "Length2", Length3: "Length3", Lighten: "Lighten", Limits: "Limits",
    Line: "Line", Linearized: "Linearized", LinearizedReader: "LinearizedReader",
    Link: "Link", ListMode: "ListMode", Location: "Location", Lock: "Lock",
    Locked: "Locked", Lookup: "Lookup", Luminosity: "Luminosity", LW: "LW",
    M: "M", MacExpertEncoding: "MacExpertEncoding", MacRomanEncoding: "MacRomanEncoding",
    Margin: "Margin", MarkInfo: "MarkInfo", Mask: "Mask", Matrix: "Matrix",
    Matte: "Matte", max: "max", MaxLen: "MaxLen", MaxWidth: "MaxWidth",
    MCID: "MCID", MediaBox: "MediaBox", Metadata: "Metadata", min: "min",
    MissingWidth: "MissingWidth", MK: "MK", ModDate: "ModDate", MouseDown: "MouseDown",
    MouseEnter: "MouseEnter", MouseExit: "MouseExit", MouseUp: "MouseUp",
    Movie: "Movie", Multiply: "Multiply", N: "N", Name: "Name", Named: "Named",
    Names: "Names", NeedAppearances: "NeedAppearances", Next: "Next",
    NextPage: "NextPage", NM: "NM", None: "None", Normal: "Normal",
    Nums: "Nums", O: "O", ObjStm: "ObjStm", OC: "OC", OCGs: "OCGs",
    OCProperties: "OCProperties",
    OE: "OE", OFF: "OFF", off: "off", ON: "ON", On: "On", OnBlur: "OnBlur",
    OnFocus: "OnFocus", OP: "OP", op: "op", Open: "Open", OpenAction: "OpenAction",
    OPI: "OPI", OPM: "OPM", Opt: "Opt", Order: "Order", Ordering: "Ordering",
    Outlines: "Outlines", Overlay: "Overlay", P: "P", PaintType: "PaintType",
    Page: "Page", PageLabels: "PageLabels", PageMode: "PageMode", Pages: "Pages",
    Params: "Params", Parent: "Parent", ParentTree: "ParentTree", Pattern: "Pattern",
    PatternType: "PatternType", PC: "PC", PDFDocEncoding: "PDFDocEncoding",
    Perms: "Perms", Pg: "Pg", PI: "PI", PieceInfo: "PieceInfo", PO: "PO",
    Polygon: "Polygon", PolyLine: "PolyLine", Popup: "Popup", Predictor: "Predictor",
    Prev: "Prev", PrevPage: "PrevPage", Print: "Print", PrinterMark: "PrinterMark",
    PrintState: "PrintState", Process: "Process", ProcSet: "ProcSet",
    Producer: "Producer", Projection: "Projection", Properties: "Properties",
    PV: "PV", Q: "Q", Qfactor: "Qfactor", QuadPoints: "QuadPoints", R: "R",
    Range: "Range", RBGroups: "RBGroups", RC: "RC", Reason: "Reason",
    Recipients: "Recipients", Rect: "Rect", Reference: "Reference",
    Registry: "Registry", ResetForm: "ResetForm", Resources: "Resources",
    RGB: "RGB", RichMedia: "RichMedia", RichMediaContent: "RichMediaContent",
    RD: "RD", RoleMap: "RoleMap", Root: "Root", Rotate: "Rotate", Rows: "Rows", RT: "RT",
    RV: "RV", S: "S", SA: "SA", Saturation: "Saturation", SaveAs: "SaveAs",
    Screen: "Screen", SetOCGState: "SetOCGState", Shading: "Shading",
    ShadingType: "ShadingType", Sig: "Sig", SigFlags: "SigFlags",
    Signed: "Signed", Size: "Size", SM: "SM", SMask: "SMask", SoftLight: "SoftLight",
    Sound: "Sound", Square: "Square", Squiggly: "Squiggly", Stamp: "Stamp",
    Standard: "Standard", StandardEncoding: "StandardEncoding", State: "State",
    StemH: "StemH", StemV: "StemV", StmF: "StmF", StrF: "StrF", StrikeOut: "StrikeOut",
    StructElem: "StructElem", StructParent: "StructParent",
    StructParents: "StructParents", StructTreeRoot: "StructTreeRoot", Style: "Style",
    SubFilter: "SubFilter", Subj: "Subj", Subject: "Subject", SubmitForm: "SubmitForm",
    Subtype: "Subtype", Supplement: "Supplement", T: "T", Tabs: "Tabs",
    TagSuspect: "TagSuspect", Text: "Text", TI: "TI", TilingType: "TilingType",
    tintTransform: "tintTransform", Title: "Title", TM: "TM", Toggle: "Toggle",
    ToUnicode: "ToUnicode", TP: "TP", TR: "TR", TrapNet: "TrapNet",
    Trapped: "Trapped", TrimBox: "TrimBox", Tx: "Tx", TxFontSize: "TxFontSize",
    TxOutline: "TxOutline", TU: "TU", Type: "Type", U: "U", UE: "UE",
    UF: "UF", Uncompressed: "Uncompressed", Unsigned: "Unsigned", Usage: "Usage",
    V: "V", Validate: "Validate", VerticesPerRow: "VerticesPerRow", View: "View",
    VIewState: "VIewState", VP: "VP", W: "W", W2: "W2", Watermark: "Watermark",
    WhitePoint: "WhitePoint", Widget: "Widget", Win: "Win",
    WinAnsiEncoding: "WinAnsiEncoding", Width: "Width", Widths: "Widths",
    WP: "WP", WS: "WS", X: "X", XFA: "XFA", XFAImages: "XFAImages", XHeight: "XHeight",
    XObject: "XObject", XRef: "XRef", XRefStm: "XRefStm", XStep: "XStep", XYZ: "XYZ",
    YStep: "YStep", Zoom: "Zoom", ZoomTo: "ZoomTo", Unchanged: "Unchanged",
    Underline: "Underline"
};
var EcmaLEX = {
    CHAR256: [
//      0, 1, 2, 3, 4, 5, 6, 7, 8, 9, A, B, C, D, E, F,
        1, 0, 0, 0, 0, 0, 0, 0, 0, 1, 1, 0, 1, 1, 0, 0, // 0
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // 1
        1, 0, 0, 0, 0, 2, 0, 0, 2, 2, 0, 0, 0, 0, 0, 2, // 2
        4, 4, 4, 4, 4, 4, 4, 4, 4, 4, 0, 0, 2, 0, 2, 0, // 3
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // 4
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 0, 0, // 5
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // 6
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 2, 0, 2, 0, 0, // 7
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // 8
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // 9
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // A
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // B
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // C
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // D
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, // E
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0  // F
    ],
    STRPDF: [
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        728, 711, 710, 729, 733, 731, 730, 732, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0,
        0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 8226, 8224, 8225, 8230, 8212,
        8211, 402, 8260, 8249, 8250, 8722, 8240, 8222, 8220, 8221, 8216, 8217,
        8218, 8482, 64257, 64258, 321, 338, 352, 376, 381, 305, 322, 339, 353,
        382, 0, 8364
    ],
    isWhiteSpace: function (ch) {
        return this.CHAR256[ch] === 1;
    },
    isEOL: function (ch) {
        return ch === 0xa || ch === 0xd;
    },
    isDelimiter: function (ch) {
        return this.CHAR256[ch] === 2;
    },
    isComment: function (ch) {
        return ch === 0x25;
    },
    isBacklash: function (ch) {
        return ch === 0x5c;
    },
    isEscSeq: function (ch1, ch2) {
        if (ch1 === 0xfc) {
            switch (ch2) {
                case 0x28: //(
                case 0x29: //)
                case 0x62: //b
                case 0x66: //f
                case 0x6e: //n
                case 0x72: //r
                case 0x74: //t           
                case 0x5c: //"/"
                case 0xc:
                case 0xd:
                    return true;
                default :
                    return false;
            }
        }
        return false;
    },
    isDigit: function (ch) {
        return this.CHAR256[ch] === 4;
    },
    isBoolean: function (x) {
        return typeof x === 'boolean';
    },
    isNull: function (x) {
        return typeof x === null;
    },
    isNumber: function (x) {
        return typeof x === 'number';
    },
    isString: function (x) {
        return typeof x === 'string';
    },
    isHexString: function (x) {
        return x instanceof EcmaHEX;
    },
    isArray: function (x) {
        return x instanceof Array;
    },
    isName: function (x) {
        return x instanceof EcmaNAME;
    },
    isDict: function (x) {
        return x instanceof EcmaDICT;
    },
    isRef: function (x) {
        return x instanceof EcmaOREF;
    },
    isStreamDict: function (dictObj) {
        return EcmaKEY.Length in dictObj.keys;
    },
    getDA: function(str){
        var obj = {fontSize:10,fontName:"Arial",fontColor:"0 g"};
        var ii = str.length;
        var i = 0;
        var ss = ""; 
        var dd = new Array();
        while (i < ii) {
            var cc = str.charCodeAt(i++);
            if (EcmaLEX.isWhiteSpace(cc) || EcmaLEX.isEOL(cc)) {
                if (ss.length > 0) {
                    dd.push(ss);
                }
                ss = "";
            } else {
                 ss = ss + String.fromCharCode(cc);                   
            }
        }
        if(ss.length>0){
            dd.push(ss);
        }
        for (var i = 0, ii = dd.length; i < ii; i++) {
            if (dd[i].charAt(0) === '/') {
                obj.fontName = dd[i];
                if(dd[i+1]){
                    obj.fontSize = parseInt(dd[i + 1]);
                }
            } else if (i > 3 && dd[i] === "rg") {
                obj.fontColor = dd[i - 3] + " " + dd[i - 2] + " " + dd[i - 1] + " rg";
            }
        }
        return obj;
    },
    getRefFromString: function (str) {
        var strs = str.trim().split(' ');
        return new EcmaOREF(parseInt(strs[0]), parseInt(strs[1]));
    },
    getZeroLead: function (num) {
        var numStr = "" + num;
        var balance = 10 - numStr.length;
        for (var i = 0; i < balance; i++) {
            numStr = "0" + numStr;
        }
        return numStr;
    },
    toPDFString: function (str) {
        var n = str.length;
        var buf = [];
        var t;
        if (str[0] === '\xFE' && str[1] === '\xFF') {
            for (var i = 2; i < n; i += 2) {
                t = (str.charCodeAt(i) << 8) | str.charCodeAt(i + 1);
                buf.push(String.fromCharCode(t));
            }
        } else {
            for (var i = 0; i < n; ++i) {
                var code = this.STRPDF[str.charCodeAt(i)];
                buf.push(code ? String.fromCharCode(code) : str.charAt(i));
            }
        }
        return buf.join('');
    },
    toBytes32: function (num) {
        return [(num & 0xff000000) >> 24, (num & 0xff0000) >> 16,
            (num & 0xff00) >> 8, (num & 0xff)];
    },
    textToBytes: function (str) {
        var arr = [];
        for (var i = 0, ii = str.length; i < ii; i++) {
            arr.push(str.charCodeAt(i));
        }
        return arr;
    },
    bytesToText: function (data, offset, len) {
        var str = "";
        for (var i = offset; i < len; i++) {
            str += String.fromCharCode(data[offset + i]);
        }
        return str;
    },
    pushBytesToBuffer: function (data, buffer) {
        for (var i = 0, ii = data.length; i < ii; i++) {
            buffer.push(data[i]);
        }
    },
    objectToText: function (obj) {
        if (this.isDict(obj)) {
            var str = "<<";
            for (var nn in obj.keys) {
                str += "/" + nn + " " + this.objectToText(obj.keys[nn]) + " ";
            }
            str += ">>";
            return str;
        } else if (this.isArray(obj)) {
            var str = "[";
            for (var i = 0, ii = obj.length; i < ii; i++) {
                str += " " + this.objectToText(obj[i]);
            }
            str += "]";
            return str;
        } else if (this.isRef(obj)) {
            return obj.ref;
        } else if (this.isName(obj)) {
            return "/" + obj.name;
        } else if (this.isNumber(obj)) {
            return "" + obj;
        } else if (this.isString(obj)) {
            return "(" + EcmaLEX.toPDFString(obj) + ")";
        } else if (this.isHexString(obj)) {
            return obj.str;
        } else if (this.isBoolean(obj)) {
            if (obj) {
                return "true";
            } else {
                return "false";
            }
        }
        return "null";
    }
};
var EcmaFontWidths = {
    Arial: [
        750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750,
        750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750, 750,
        750, 750, 750, 750, 278, 278, 355, 556, 556, 889, 667, 191, 333, 333,
        389, 584, 278, 333, 278, 278, 556, 556, 556, 556, 556, 556, 556, 556,
        556, 556, 278, 278, 584, 584, 584, 556, 1015, 667, 667, 722, 722, 667,
        611, 778, 722, 278, 500, 667, 556, 833, 722, 778, 667, 778, 722, 667,
        611, 722, 667, 944, 667, 667, 611, 278, 278, 278, 469, 556, 333, 556,
        556, 500, 556, 556, 278, 556, 556, 222, 222, 500, 222, 833, 556, 556,
        556, 556, 333, 500, 278, 556, 500, 722, 500, 500, 500, 334, 260, 334,
        584, 350, 556, 350, 222, 556, 333, 1000, 556, 556, 333, 1000, 667, 333,
        1000, 350, 611, 350, 350, 222, 222, 333, 333, 350, 556, 1000, 333, 1000,
        500, 333, 944, 350, 500, 667, 278, 333, 556, 556, 556, 556, 260, 556,
        333, 737, 370, 556, 584, 333, 737, 552, 400, 549, 333, 333, 333, 576,
        537, 333, 333, 333, 365, 556, 834, 834, 834, 611, 667, 667, 667, 667,
        667, 667, 1000, 722, 667, 667, 667, 667, 278, 278, 278, 278, 722, 722,
        778, 778, 778, 778, 778, 584, 778, 722, 722, 722, 722, 667, 667, 611,
        556, 556, 556, 556, 556, 556, 889, 500, 556, 556, 556, 556, 278, 278,
        278, 278, 556, 556, 556, 556, 556, 556, 556, 549, 611, 556, 556, 556,
        556, 500, 556, 500
    ]
};
var EcmaFormField = {
    READONLY_ID: 1,
    REQUIRED_ID: 2,
    NOEXPORT_ID: 3,
    MULTILINE_ID: 13,
    PASSWORD_ID: 14,
    NOTOGGLETOOFF_ID: 15,
    RADIO_ID: 16,
    PUSHBUTTON_ID: 17,
    COMBO_ID: 18,
    EDIT_ID: 19,
    SORT_ID: 20,
    FILESELECT_ID: 21,
    MULTISELECT_ID: 22,
    DONOTSPELLCHECK_ID: 23,
    DONOTSCROLL_ID: 24,
    COMB_ID: 25,
    RICHTEXT_ID: 26,
    RADIOINUNISON_ID: 26,
    COMMITONSELCHANGE_ID: 27,
    editTextField: function (fieldDicts, fieldRefs, fieldDict, fieldText, maxUsed) {
        var isPassword = false;
        var isMulti = false;
        var appearText = fieldText;

        var ff = fieldDict.keys[EcmaKEY.Ff];
        if (ff) {
            var fArr = this.flagToBooleans(ff);
            fArr [1] = true;
            isPassword = fArr[this.PASSWORD_ID];
            if (isPassword) {
                var temp = "";
                for (var i = 0, ii = appearText.length; i < ii; i++) {
                    temp += "*";
                }
                appearText = temp;
            }
            isMulti = fArr[this.MULTILINE_ID];
        }
        fieldDict.keys[EcmaKEY.V] = fieldText;
        fieldDict.keys[EcmaKEY.AP] = new EcmaDICT();
        var rect = fieldDict.keys[EcmaKEY.Rect];
        var fw = rect[2] - rect[0];
        var fh = rect[3] - rect[1];
        var fontSize = 10;
        var daStr = fieldDict.keys["DA"];
        var parent = fieldDict.keys[EcmaKEY.Parent];
        if(EcmaLEX.isRef(parent)){
            var temp = new EcmaBuffer();
            var parD = temp.getIndirectObject(parent);
            if(EcmaLEX.isDict(parD) && parD.keys[EcmaKEY.V]){
                parD.keys[EcmaKEY.V] = fieldText;
                fieldDicts.push(parD);
                fieldRefs.push(parent);
            }
        }
        var fColor = "0 g";
        if (daStr) {            
            var daObj = (EcmaLEX.getDA(daStr));
            fontSize = daObj.fontSize;
            fontSize = fontSize === 0 ? 10 : fontSize;
            fColor = daObj.fontColor;
            fieldDict.keys["DA"] = "/Arial " + fontSize + " Tf "+fColor;
        }
        var nDict = new EcmaDICT();
        var nDictRef = new EcmaOREF(maxUsed, 0);

        fieldDict.keys[EcmaKEY.AP].keys["N"] = nDictRef;
        nDict.keys[EcmaKEY.BBox] = [0, 0, fw, fh];
        nDict.keys[EcmaKEY.Matrix] = [1, 0, 0, 1, 0, 0];
        nDict.keys[EcmaKEY.Subtype] = new EcmaNAME(EcmaKEY.Form);
        nDict.keys[EcmaKEY.Resources] = new EcmaDICT();
        nDict.keys[EcmaKEY.FormType] = 1;
        nDict.keys[EcmaKEY.Type] = new EcmaNAME(EcmaKEY.XObject);
        var nStr = "";
        if (isMulti) {
            var temp, curW = 0, output = "", curWord = "", curLine = "";
            for (var i = 0, ii = appearText.length; i < ii; i++) {
                var cc = appearText.charCodeAt(i);
                var ch = String.fromCharCode(cc);
                var cw = this.findCodeWidth(cc, fontSize);
                if ((curW + cw) > fw) {
                    if (curWord === curLine) {
                        output += curWord + "\n";
                        curWord = "";
                        curLine = "";
                        curW = 0;
                    } else {
                        output += "\n";
                        curW = this.findStringWidth(curWord, fontSize);
                        curLine = curWord;
                    }
                }
                curW += cw;
                if (cc === 0xa) {
                    output += curWord + "\n";
                    curWord = "";
                    curLine = "";
                    curW = 0;
                } else if (EcmaLEX.isWhiteSpace(cc)) {
                    output += curWord + " ";
                    curWord = "";
                    curLine += " ";
                } else {
                    curWord += ch;
                    curLine += ch;
                }
            }
            if (curWord.length > 0) {
                output += curWord;
            }
            var outputs = output.split("\n");
            var lineH = fontSize * 1.2;
            var paraH = outputs.length * lineH;
            var startY = (fh - paraH) / 2 + paraH - fontSize;
            startY = startY < 0 ? 0 : startY;
            nStr += "/Tx BMC\nBT\n/Arial " + fontSize + " Tf\n "+fColor+"\n"; //prefix
            nStr += "2 " + startY + " Td\n(" + outputs[0] + ") Tj\n";
            for (var i = 1, ii = outputs.length; i < ii; i++) {
                nStr += "0 " + (-lineH) + " Td\n(" + outputs[i] + ") Tj\n";
            }
            nStr += "ET\nEMC"; //suffix
        } else {
            var desc = (fontSize - fontSize * 0.2); //0.2 is baseline percentage
            var mid = (fh - desc) > 0 ? (fh - desc) / 2 : 0;
            nStr = "/Tx BMC\nBT\n/Arial " + fontSize
                    + " Tf\n "+fColor+"\n2 " + mid + " Td\n(" + appearText + ") Tj\nET\nEMC";
        }

        var nBytes = EcmaLEX.textToBytes(nStr);
        nDict.keys[EcmaKEY.Length] = nBytes.length;
        nDict.rawStream = nBytes;
        nDict.stream = nBytes;

        fieldDicts.push(nDict);
        fieldRefs.push(nDictRef);
    },
    selectCheckBox: function (curSelect, fieldDict) {
        var onVal = "Yes";
        var offVal = "Off";
        var ap = fieldDict.keys[EcmaKEY.AP];
        if (ap) {
            var d = ap.keys[EcmaKEY.D];
            if (d) {
                for (var dk  in d.keys) {
                    var lc = dk.toLowerCase();
                    if (lc !== "off") {
                        onVal = dk;
                    }
                }
            }
            if (curSelect) {
                fieldDict.keys[EcmaKEY.AS] = new EcmaNAME(onVal);
                fieldDict.keys[EcmaKEY.V] = new EcmaNAME(onVal);
            } else {
                fieldDict.keys[EcmaKEY.AS] = new EcmaNAME(offVal);
                fieldDict.keys[EcmaKEY.V] = new EcmaNAME(offVal);
            }
        }
    },
    selectRadioChild: function (isChecked, fieldDict) {
        var ap = fieldDict.keys[EcmaKEY.AP];
        if (ap) {
            var onVal = "Yes";
            var offVal = "Off";
            var d = ap.keys[EcmaKEY.D];
            if (d) {
                for (var dk  in d.keys) {
                    var lc = dk.toLowerCase();
                    if (lc !== "off") {
                        onVal = dk;
                    }
                }
            }
            if (isChecked) {
                fieldDict.keys[EcmaKEY.AS] = new EcmaNAME(onVal);
            } else {
                fieldDict.keys[EcmaKEY.AS] = new EcmaNAME(offVal);
            }
        }
    },
    selectChoice: function (fieldDicts, fieldRefs, fieldDict, curValue, maxUsed) {

        var opt = fieldDict.keys[EcmaKEY.Opt];
        var appearText = curValue;
        fieldDict.keys[EcmaKEY.V] = curValue;

        if (opt) {
            for (var i = 0, ii = opt.length; i < ii; i++) {
                var tt = opt[i];
                if (EcmaLEX.isArray(tt)) {
                    if (tt[0] === curValue) {
                        appearText = tt[1];
                        fieldDict.keys[EcmaKEY.I] = [i];
                        break;
                    }
                } else {
                    if (tt === curValue) {
                        appearText = tt;
                        fieldDict.keys[EcmaKEY.I] = [i];
                        break;
                    }
                }
            }
        }

        fieldDict.keys[EcmaKEY.AP] = new EcmaDICT();
        var rect = fieldDict.keys[EcmaKEY.Rect];
        var fw = rect[2] - rect[0];
        var fh = rect[3] - rect[1];
        var fontSize = 10;
        var daStr = fieldDict.keys["DA"];
        if (daStr) {
            var ptr = daStr.indexOf("/");
            if (ptr >= 0) {
                daStr = daStr.substring(ptr).split(' ');
                fontSize = parseInt(daStr[1]);
            }
            fieldDict.keys["DA"] = "/Arial " + fontSize + " Tf";
        }
        var nDict = new EcmaDICT();
        var nDictRef = new EcmaOREF(maxUsed, 0);

        fieldDict.keys[EcmaKEY.AP].keys["N"] = nDictRef;
        nDict.keys[EcmaKEY.BBox] = [0, 0, fw, fh];
        nDict.keys[EcmaKEY.Matrix] = [1, 0, 0, 1, 0, 0];
        nDict.keys[EcmaKEY.Subtype] = new EcmaNAME(EcmaKEY.Form);
        nDict.keys[EcmaKEY.Resources] = new EcmaDICT();
        nDict.keys[EcmaKEY.FormType] = 1;
        nDict.keys[EcmaKEY.Type] = new EcmaNAME(EcmaKEY.XObject);

        var desc = (fontSize - fontSize * 0.2); //0.2 is baseline percentage
        var mid = (fh - desc) > 0 ? (fh - desc) / 2 : 0;
        var nStr = "/Tx BMC\nBT\n/Arial " + fontSize
                + " Tf\n0 g\n2 " + mid + " Td\n(" + appearText + ") Tj\nET\nEMC";

        var nBytes = EcmaLEX.textToBytes(nStr);
        nDict.keys[EcmaKEY.Length] = nBytes.length;
        nDict.rawStream = nBytes;
        nDict.stream = nBytes;

        fieldDicts.push(nDict);
        fieldRefs.push(nDictRef);
    },
    findStringWidth: function (str, fontSize) {
        var tw = 0;
        for (var i = 0, ii = str.length; i < ii; i++) {
            var ch = str.charCodeAt(i);
            if (ch < 256) {
                tw += EcmaFontWidths.Arial[ch] / 1000 * fontSize;
            } else {
                tw += fontSize;
            }
        }
        return tw;
    },
    findCodeWidth: function (charCode, fontSize) {
        return charCode < 256 ? (EcmaFontWidths.Arial[charCode] / 1000 * fontSize) : fontSize;
    },
    flagToBooleans: function (flag) {
        var bools = [false];
        for (var i = 0; i < 32; i++) {
            bools[i + 1] = (flag & (1 << i)) === (1 << i);
        }
        return bools;
    },
    booleansToFlag: function (bools) {
        var res = 0;
        for (var i = 0; i < 32; i++) {
            res = (bools[32 - i]) ? (res << 1) | 1 : res = res << 1;
        }
        return res;
    }
};
var EcmaNAMES = {};
var EcmaOBJECTOFFSETS = {};
var EcmaPageOffsets = [];
var EcmaFieldOffsets = [];
var EcmaMainCatalog = null;
var EcmaMainData = [];
var EcmaXRefType = 0; //zero for old and 1 for stream;

function showEcmaParserError(e) {
    alert(e);
}

function EcmaOBJOFF(offset, data, isStream) {
    this.data = data;
    this.offset = offset;
    this.isStream = isStream;
}
function EcmaDICT() {
    this.keys = {};
    this.stream = null;
    this.rawStream = null;
}
function EcmaNAME(name) {
    this.name = name;
    this.value = null;
}
function EcmaOREF(r1, r2) {
    this.num = r1;
    this.gen = r2;
    this.ref = r1 + " " + r2 + " R";
}
function EcmaHEX(str) {
    this.str = str;
}

function EcmaBuffer(data) {
    this.data = data;
    this.pos = 0;
    this.markPos = 0;
    this.length = 0;
    if (data) {
        this.length = data.length;
    }
}

EcmaBuffer.prototype.getByte = function () {
    return (this.pos >= this.length) ? -1 : this.data[this.pos++];
};
EcmaBuffer.prototype.mark = function () {
    this.markPos = this.pos;
};
EcmaBuffer.prototype.reset = function () {
    this.pos = this.markPos;
};
EcmaBuffer.prototype.movePos = function (x) {
    this.pos = x;
};
EcmaBuffer.prototype.readTo = function (bb) {
    var avail = this.length - this.pos;
    var max = Math.min(bb.length, avail);
    for (var i = 0; i < max; i++) {
        bb[i] = this.getByte();
    }
};
EcmaBuffer.prototype.readTo = function (bb, offset, len) {
    if (this.pos < this.length) {
        var n = 0;
        var avail = this.length - this.pos;
        var max = Math.min(len, avail);
        for (var i = 0; i < max; i++) {
            bb[offset + i] = this.getByte();
            n++;
        }
        return n;
    } else {
        return -1;
    }
};
EcmaBuffer.prototype.lookNext = function () {
    return (this.pos >= this.length) ? -1 : this.data[this.pos];
};
EcmaBuffer.prototype.lookNextNext = function () {
    return (this.pos >= this.length) ? -1 : this.data[this.pos + 1];
};
EcmaBuffer.prototype.getNextLine = function () {
    var bb = "";
    var v = this.getByte();
    while (true) {
        if (v === 0xd) {
            v = this.getByte();
            if (v === 0xa) {
                break;
            }
        } else if (v === 0xa) {
            break;
        } else {
            bb += String.fromCharCode(v);
            v = this.getByte();
        }
    }
    return bb;
};
EcmaBuffer.prototype.skipLine = function () {
    var v = this.getByte();
    while (true) {
        if (v === -1) {
            break;
        }
        else if (v === 0xd) {
            v = this.lookNext();
            if (v === 0xa) {
                this.getByte();
                break;
            }
            break;
        } else if (v === 0xa) {
            break;
        } else {
            v = this.getByte();
        }
    }
};
EcmaBuffer.prototype.getNumberValue = function () {
    var v = this.getByte();
    var multi = 1;
    var startFloat = false;
    if (v === 0x2b) { //+
        v = this.getByte();
    } else if (v === 0x2d) { //-
        multi = -1;
        v = this.getByte();
    }
    if (v === 0x2e) { //.
        startFloat = true;
        v = this.getByte();
    }
    if (v < 0x30 || v > 0x39) {
        return 0;
    }
    var numStr = "" + String.fromCharCode(v);
    while (true) {
        var n = this.lookNext();
        if (n === 0x2e || EcmaLEX.isDigit(n)) {
            v = this.getByte();
            numStr += ("" + String.fromCharCode(v));
        } else {
            break;
        }
    }
    if (startFloat) {
        return multi * parseFloat("0." + numStr);
    } else if (numStr.indexOf(".") !== -1) {
        return multi * parseFloat(numStr);
    } else {
        return multi * parseInt(numStr);
    }
};
EcmaBuffer.prototype.getNameValue = function () {
    var nameStr = "";
    this.getByte(); //read / first;
    var n;
    while (true) {
        n = this.lookNext();
        if (n >= 0 && !EcmaLEX.isDelimiter(n) && !EcmaLEX.isWhiteSpace(n)) {
            nameStr += String.fromCharCode(this.getByte());
        } else {
            break;
        }
    }
    return nameStr;
};
EcmaBuffer.prototype.getNormalString = function () {
    var bb = [];
    this.getByte(); //read ( first;
    var pCount = 1;
    var v = this.getByte();
    var finished = false;
    while (true) {
        var isBuffering = false;
        switch (v) {
            case -1:
                finished = true;
                break;
            case 0x28:
                bb.push('(');
                pCount++;
                break;
            case 0x29:
                pCount--;
                if (pCount) {
                    bb.push(')');
                } else {
                    finished = true;
                }
                break;
            case 0x5c:
                v = this.getByte();
                switch (v) {
                    case -1:
                        finished = true;
                        break;
                    case 0x28:
                    case 0x29:
                    case 0x5C:
                        bb.push(String.fromCharCode(v));
                        break;
                    case 0x6E:
                        bb.push('\n');
                        break;
                    case 0x72:
                        bb.push('\r');
                        break;
                    case 0x74:
                        bb.push('\t');
                        break;
                    case 0x62:
                        bb.push('\b');
                        break;
                    case 0x66:
                        bb.push('\f');
                        break;
                    case 0x30:
                    case 0x31:
                    case 0x32:
                    case 0x33:
                    case 0x34:
                    case 0x35:
                    case 0x36:
                    case 0x37:
                        var x = v & 0x0F;
                        v = this.getByte();
                        isBuffering = true;
                        if (v >= 0x30 && v <= 0x37) {
                            x = (x << 3) + (v & 0x0F);
                            v = this.getByte();
                            if (v >= 0x30 && v <= 0x37) {
                                isBuffering = false;
                                x = (x << 3) + (v & 0x0F);
                            }
                        }
                        bb.push(String.fromCharCode(x));
                        break;
                    case 0x0D:
                        if (this.lookNext() === 0x0A) {
                            this.getByte();
                        }
                        break;
                    case 0x0A:
                        break;
                    default:
                        bb.push(String.fromCharCode(v));
                        break;
                }
                break;
            default:
                bb.push(String.fromCharCode(v));
        }
        if (finished) {
            break;
        }
        if (!isBuffering) {
            v = this.getByte();
        }
    }
    return bb.join('');
};
EcmaBuffer.prototype.getHexString = function () {
    this.getByte(); //read <
    var ch = this.getByte();
    var hexStr = "<";
    while (true) {
        if (ch < 0 || ch === 0x3e) {
            hexStr += ">";
            break;
        } else if (EcmaLEX.isWhiteSpace(ch)) {
            ch = this.getByte();
            continue;
        } else {
            hexStr += String.fromCharCode(ch);
            ch = this.getByte();
        }
    }
    return new EcmaHEX(hexStr);
};
EcmaBuffer.prototype.getDictionary = function () {
    var dictObj = new EcmaDICT();
    this.getByte(); //read <
    this.getByte(); //read < again
    var nameBuf = [];
    var canRead = true;
    while (canRead) {
        var n = this.lookNext();
        switch (n) {
            case -1:
                return dictObj;
            case 0x30:
            case 0x31:
            case 0x32:
            case 0x33:
            case 0x34:
            case 0x35:
            case 0x36:
            case 0x37:
            case 0x38:
            case 0x39:
            case 0x2b:
            case 0x2d:
            case 0x2e:
                var n1 = this.getNumberValue();
                var sp = this.lookNext();
                var n2 = this.lookNextNext();
                if (nameBuf.length > 0) {
                    var k = nameBuf.pop();
                    var key = k.name;
                    if (EcmaLEX.isWhiteSpace(sp) && EcmaLEX.isDigit(n2)) {
                        this.getByte(); //space
                        n2 = this.getNumberValue();
                        this.getByte(); //space
                        this.getByte(); //R
                        dictObj.keys[key] = new EcmaOREF(n1, n2);
                    } else {
                        dictObj.keys[key] = (n1);
                    }
                }
                break;
            case 0x2f:
                //now read the name
                var str = this.getNameValue();
                var en;
                if (EcmaNAMES[str]) {
                    en = EcmaNAMES[str];
                } else {
                    en = new EcmaNAME(str);
                    EcmaNAMES[str] = en;
                }
                if (nameBuf.length === 0) {
                    nameBuf.push(en);
                } else {
                    var k = nameBuf.pop();
                    var key = k.name;
                    dictObj.keys[key] = en;
                }
                break;
            case 0x28:
                var nStr = this.getNormalString();
                if (nameBuf.length !== 0) {
                    var k = nameBuf.pop();
                    var key = k.name;
                    dictObj.keys[key] = nStr;
                }
                break;
            case 0x3c:
                if (this.lookNextNext() === 0x3c) {
                    var dict = this.getDictionary();
                    if (nameBuf.length !== 0) {
                        var k = nameBuf.pop();
                        var key = k.name;
                        dictObj.keys[key] = dict;
                    }
                } else {
                    var hStr = this.getHexString();
                    if (nameBuf.length !== 0) {
                        var k = nameBuf.pop();
                        var key = k.name;
                        dictObj.keys[key] = hStr;
                    }
                }
                break;
            case 0x5b:
                var arr = this.getArray();
                if (nameBuf.length !== 0) {
                    var k = nameBuf.pop();
                    var key = k.name;
                    dictObj.keys[key] = arr;
                }
                break;
            case 0x74:
                //below is true;
                if (this.data[this.pos + 1] === 0x72 && this.data[this.pos + 2] === 0x75
                        && this.data[this.pos + 3] === 0x65) {
                    for (var i = 0; i < 4; i++) {
                        this.getByte();
                    }
                    if (nameBuf.length > 0) {
                        var k = nameBuf.pop();
                        var key = k.name;
                        dictObj.keys[key] = true;
                    }
                } else {
                    this.getByte();
                }
                break;
            case 0x66://below is false;
                if (this.data[this.pos + 1] === 0x61 && this.data[this.pos + 2] === 0x6c
                        && this.data[this.pos + 3] === 0x73 && this.data[this.pos + 4] === 0x65) {
                    for (var i = 0; i < 5; i++) {
                        this.getByte();
                    }
                    if (nameBuf.length > 0) {
                        var k = nameBuf.pop();
                        var key = k.name;
                        dictObj.keys[key] = false;
                    }
                } else {
                    this.getByte();
                }
                break;
            case 0x6e://below is null
                if (this.data[this.pos + 1] === 0x75 && this.data[this.pos + 2] === 0x6c
                        && this.data[this.pos + 3] === 0x6c) {
                    for (var i = 0; i < 4; i++) {
                        this.getByte();
                    }
                    if (nameBuf.length > 0) {
                        var k = nameBuf.pop();
                        var key = k.name;
                        dictObj.keys[key] = null;
                    }
                } else {
                    this.getByte();
                }
                break;
            case 0x3e:
                this.getByte();
                if (this.lookNext() === 0x3e) { //end of dictionary
                    this.getByte();
                    canRead = false;
                }
                break;
            default:
                this.getByte();
                break;
        }
    }
    if (EcmaLEX.isStreamDict(dictObj) && !dictObj.stream) {
        dictObj.stream = this.getStream(dictObj);
    }
    return dictObj;
};
EcmaBuffer.prototype.getArray = function () {
    this.getByte(); //read and ignore [
    var res = [];
    while (true) {
        var n = this.lookNext();
        switch (n) {
            case -1:
                return res;
            case 0x30:
            case 0x31:
            case 0x32:
            case 0x33:
            case 0x34:
            case 0x35:
            case 0x36:
            case 0x37:
            case 0x38:
            case 0x39:
            case 0x2b:
            case 0x2d:
            case 0x2e:
                var n1 = this.getNumberValue();
                var sp = this.data[this.pos]; //could be space
                var n2 = this.data[this.pos + 1]; //could be digit
                if (EcmaLEX.isWhiteSpace(sp) && EcmaLEX.isDigit(n2)) {
                    this.mark();
                    this.getByte(); //space
                    n2 = this.getNumberValue();
                    this.getByte(); //space
                    var n3 = this.getByte(); //R
                    var n4 = this.lookNext(); //any whitespace or delimiter
                    if (n3 === 0x52 && (EcmaLEX.isWhiteSpace(n4) || EcmaLEX.isDelimiter(n4))) {
                        res.push(new EcmaOREF(n1, n2));
                    } else {
                        res.push(n1);
                        this.reset();
                    }
                } else {
                    res.push(n1);
                }
                break;
            case 0x2f:
                var str = this.getNameValue();
                if (EcmaNAMES[str]) {//                   
                } else {
                    EcmaNAMES[str] = new EcmaNAME(str);
                }
                res.push(EcmaNAMES[str]);
                break;
            case 0x28:
                res.push(this.getNormalString());
                break;
            case 0x3c:
                if (this.lookNextNext() === 0x3c) {
                    var dict = this.getDictionary();
                    res.push(dict);
                } else {
                    res.push(this.getHexString());
                }
                break;
            case 0x5b:
                res.push(this.getArray());
                break;
            case 0x5d:
                this.getByte();
                return res;
            case 0x74:
                //below is true;
                if (this.data[this.pos + 1] === 0x72 && this.data[this.pos + 2] === 0x75
                        && this.data[this.pos + 3] === 0x65) {
                    res.push(true);
                    for (var i = 0; i < 4; i++) {
                        this.getByte();
                    }
                } else {
                    this.getByte();
                }
                break;
            case 0x66:
                //below is false;
                if (this.data[this.pos + 1] === 0x61 && this.data[this.pos + 2] === 0x6c
                        && this.data[this.pos + 3] === 0x73 && this.data[this.pos + 4] === 0x65) {
                    res.push(false);
                    for (var i = 0; i < 5; i++) {
                        this.getByte();
                    }
                } else {
                    this.getByte();
                }
                break;
            case 0x6e:
                //below is null
                if (this.data[this.pos + 1] === 0x75 && this.data[this.pos + 2] === 0x6c
                        && this.data[this.pos + 3] === 0x6c) {
                    res.push(null);
                    for (var i = 0; i < 4; i++) {
                        this.getByte();
                    }
                } else {
                    this.getByte();
                }
            default:
                this.getByte();
                break;
        }
    }
};
EcmaBuffer.prototype.getStream = function (dictObj) {
    while (true) {
        var n = this.lookNext();
        if (n === 0x73 && this.data[this.pos + 1] === 0x74
                && this.data[this.pos + 2] === 0x72
                && this.data[this.pos + 3] === 0x65
                && this.data[this.pos + 4] === 0x61
                && this.data[this.pos + 5] === 0x6d) {
            for (var i = 0; i < 6; i++) {
                this.getByte();
            }
            break;
        } else if (n === -1) {
            return null;
        } else {
            this.getByte();
        }
    }
    this.skipLine();
    var len = this.getObjectValue(dictObj.keys[EcmaKEY.Length]);
    var res = new Array(len);
    for (var i = 0; i < len; i++) {
        res[i] = this.getByte() & 0xff;
    }

    var raw = new Array(len);
    for (var i = 0; i < len; i++) {
        raw[i] = res[i];
    }
    dictObj.rawStream = raw;
    while (true) {
        var n = this.lookNext();
        if (n === -1) {
            break;
        } else if (n === 0x65
                && this.data[this.pos + 1] === 0x6e
                && this.data[this.pos + 2] === 0x64
                && this.data[this.pos + 3] === 0x73
                && this.data[this.pos + 4] === 0x74
                && this.data[this.pos + 5] === 0x72
                && this.data[this.pos + 6] === 0x65
                && this.data[this.pos + 7] === 0x61
                && this.data[this.pos + 8] === 0x6d) {

            for (var i = 0; i < 9; i++) {
                this.getByte();
            }
            break;
        } else {
            this.getByte();
        }
    }
    var filter = dictObj.keys[EcmaKEY.Filter];
    if (filter) {
        if (filter instanceof Array) {
            for (var i = 0, ii = filter.length; i < ii; i++) {
                res = EcmaFilter.decode(filter[i].name, res);
            }
        } else {
            res = EcmaFilter.decode(filter.name, res);
        }
    }
    var decodeParms = dictObj.keys[EcmaKEY.DecodeParms];
    if (decodeParms) {
        var predictor = 1;
        var colors = 1;
        var bitsPerComponent = 8;
        var columns = 1;
        var earlyChange = 1;
        if (decodeParms instanceof Array) {
            for (var i = 0, ii = decodeParms.length; i < ii; i++) {
                var decodeDict = this.getObjectValue(decodeParms[i]);
                var tt = decodeDict.keys[EcmaKEY.Predictor];
                if (tt) {
                    predictor = tt;
                }
                tt = decodeDict.keys[EcmaKEY.Colors];
                if (tt) {
                    colors = tt;
                }
                tt = decodeDict.keys[EcmaKEY.BitsPerComponent];
                if (tt) {
                    bitsPerComponent = tt;
                }
                tt = decodeDict.keys[EcmaKEY.Columns];
                if (tt) {
                    columns = tt;
                }
                tt = decodeDict.keys[EcmaKEY.EarlyChange];
                if (tt) {
                    earlyChange = tt;
                }
            }
        } else {
            var decodeDict = this.getObjectValue(decodeParms);
            var tt = decodeDict.keys[EcmaKEY.Predictor];
            if (tt) {
                predictor = tt;
            }
            tt = decodeDict.keys[EcmaKEY.Colors];
            if (tt) {
                colors = tt;
            }
            tt = decodeDict.keys[EcmaKEY.BitsPerComponent];
            if (tt) {
                bitsPerComponent = tt;
            }
            tt = decodeDict.keys[EcmaKEY.Columns];
            if (tt) {
                columns = tt;
            }
            tt = decodeDict.keys[EcmaKEY.EarlyChange];
            if (tt) {
                earlyChange = tt;
            }
        }
        if (predictor !== 1) {
            var count = EcmaFilter.applyPredictor(res, predictor, null, colors, bitsPerComponent, columns, earlyChange);
            var bos = EcmaFilter.createByteBuffer(count);
            EcmaFilter.applyPredictor(res, predictor, bos, colors, bitsPerComponent, columns, earlyChange);
        }

        res = bos;
    }
    return res;
};
EcmaBuffer.prototype.getObjectValue = function (obj) {
    if (EcmaLEX.isName(obj)) {
        return obj.name;
    } else if (EcmaLEX.isDict(obj)) {
        return obj;
    } else if (EcmaLEX.isRef(obj)) {
        var io = this.getIndirectObject(obj, this.data, false);
        return this.getObjectValue(io);
    } else {
        return obj;
    }
};

EcmaBuffer.prototype.getIndirectObject = function (objRef) {
    for (var items in EcmaOBJECTOFFSETS) {
        if (items === objRef.ref) {
            var objoff = EcmaOBJECTOFFSETS[items];
            var offset = objoff.offset;
            var subBuffer = new EcmaBuffer(objoff.data);
            var isStream = objoff.isStream;
            if (isStream) {
                if (objoff.data) {
                    subBuffer.movePos(offset);
                    return subBuffer.getObj();
                } else {
                    return null;
                }
            } else { //normal so need to read obj and endobj keywords
                subBuffer.movePos(offset);
                while (true) {
                    var n = subBuffer.lookNext();
                    if (n === -1) {
                        return null;
                    } else if (n === 0x6f && subBuffer.data[subBuffer.pos + 1] === 0x62
                            && subBuffer.data[subBuffer.pos + 2] === 0x6a) {
                        for (var i = 0; i < 3; i++) {
                            subBuffer.getByte();
                        }
                        break;
                    } else {
                        subBuffer.getByte();
                    }
                }
                return subBuffer.getObj();
            }
        }
    }
    return null;
};
EcmaBuffer.prototype.getObj = function () {
    while (true) {
        var n = this.lookNext();
        switch (n) {
            case -1:
                return null;
            case 0x30:
            case 0x31:
            case 0x32:
            case 0x33:
            case 0x34:
            case 0x35:
            case 0x36:
            case 0x37:
            case 0x38:
            case 0x39:
            case 0x2b:
            case 0x2d:
            case 0x2e:
                var n1 = this.getNumberValue();
                var sp = this.lookNext();
                var n2 = this.lookNextNext();
                var sp2 = this.data[this.pos + 2];
                var sp3 = this.data[this.pos + 3];
                if (EcmaLEX.isWhiteSpace(sp) && EcmaLEX.isDigit(n2)
                        && EcmaLEX.isWhiteSpace(sp2) && sp3 === 82) {
                    this.getByte();
                    n2 = this.getNumberValue();
                    this.getByte();
                    this.getByte();
                    return new EcmaOREF(n1, n2);
                } else {
                    return n1;
                }
            case 0x2f:
                return this.getNameValue();
            case 0x28:
                return this.getNormalString();
            case 0x3c:
                if (this.lookNextNext() === 0x3c) {
                    return this.getDictionary();
                } else {
                    return this.getHexString();
                }
            case 0x5b:
                return this.getArray();
            case 0x74:
                //below is true;
                if (this.data[this.pos + 1] === 0x72 && this.data[this.pos + 2] === 0x75
                        && this.data[this.pos + 3] === 0x65) {
                    for (var i = 0; i < 4; i++) {
                        this.getByte();
                    }
                    return true;
                } else {
                    this.getByte();
                }
                break;
            case 0x66:
                //below is false;
                if (this.data[this.pos + 1] === 0x61 && this.data[this.pos + 2] === 0x6c
                        && this.data[this.pos + 3] === 0x73 && this.data[this.pos + 4] === 0x65) {
                    for (var i = 0; i < 5; i++) {
                        this.getByte();
                    }
                    return false;
                } else {
                    this.getByte();
                }
            case 0x6e:
                //below is null
                if (this.data[this.pos + 1] === 0x75 && this.data[this.pos + 2] === 0x6c
                        && this.data[this.pos + 3] === 0x6c) {
                    for (var i = 0; i < 4; i++) {
                        this.getByte();
                    }
                    return null;
                } else {
                    this.getByte();
                }
            default:
                this.getByte();
                break;
        }
    }
    return null;
};
EcmaBuffer.prototype.readSimpleXREF = function () {
    var nn = this.lookNext();
    if (EcmaLEX.isDigit(nn)) {
        this.readStreamXREF();
        return;
    }
    this.skipLine(); // reads xref command;  
    var trailer = null;
    while (true) {
        var n = this.lookNext();
        if (n === -1) {
            break;
        } else if (EcmaLEX.isEOL(n)) {
            this.skipLine();
            continue;
        } else if (n === 0x74
                && this.data[this.pos + 1] === 0x72
                && this.data[this.pos + 2] === 0x61
                && this.data[this.pos + 3] === 0x69
                && this.data[this.pos + 4] === 0x6c
                && this.data[this.pos + 5] === 0x65
                && this.data[this.pos + 6] === 0x72) {
            trailer = this.getObj();
            break;
        }
        var start = this.getObj();
        this.getByte();
        var count = this.getObj();
        this.skipLine();
        for (var i = 0; i < count; i++) {
            var offset = this.getObj();
            var gen = this.getObj();
            var end = this.getNextLine();
            end = end.trim();
            var ref = (start + i) + " " + gen + " R";
            if (end === "n" && !EcmaOBJECTOFFSETS[ref]) {
                EcmaOBJECTOFFSETS[ref] = new EcmaOBJOFF(offset, this.data, false);
            }
        }
    }
    if (trailer) {
        if (!EcmaMainCatalog) {
            EcmaMainCatalog = trailer.keys["Root"];
        }
        var prev = trailer.keys[EcmaKEY.Prev];
        if (prev) {
            var prevOffset = this.getObjectValue(prev);
            this.movePos(prevOffset);
            this.readSimpleXREF();
        }
    } else {
        showEcmaParserError("Trailer not found");
    }
};

EcmaBuffer.prototype.readStreamXREF = function () {
    EcmaXRefType = 1;
    this.getObj();
    this.getObj();
    var refDict = this.getObj();
    var res = refDict.stream;
    var wArr = refDict.keys[EcmaKEY.W];
    var indexArr = refDict.keys[EcmaKEY.Index];
    if (!indexArr) {
        indexArr = [0, refDict.keys[EcmaKEY.Size]];
    }
    var typeLen = wArr[0];
    var offsetLen = wArr[1];
    var genLen = wArr[2];
    var total = indexArr.length;
    var p = 0;

    var buf = new EcmaBuffer(res);

    while (total > p) {
        var start = indexArr[p++];
        var end = start + indexArr[p++];
        for (var i = start; i < end; i++) {
            var type = 0, offset = 0, gen = 0;
            if (typeLen === 0) {
                type = 1;
            } else {
                for (var j = 0; j < typeLen; j++) {
                    type = (type << 8) | buf.getByte();
                }
            }
            for (var j = 0; j < offsetLen; j++) {
                offset = (offset << 8) | buf.getByte();
            }
            for (var j = 0; j < genLen; j++) {
                gen = (gen << 8) | buf.getByte();
            }
            var entryRef = (i) + " " + gen + " R";
            if (!EcmaOBJECTOFFSETS[entryRef]) {
                switch (type) {
                    case 0:
                        break;
                    case 1:
                        EcmaOBJECTOFFSETS[entryRef] = new EcmaOBJOFF(offset, EcmaMainData, false);
                        break;
                    case 2:
                        EcmaOBJECTOFFSETS[entryRef] = new EcmaOBJOFF(offset, null, true);
                        break;
                }
            }
        }
    }
    if (!EcmaMainCatalog) {
        EcmaMainCatalog = refDict.keys["Root"];
    }
    var prev = refDict.keys[EcmaKEY.Prev];
    if (prev) {
        var prevOffset = this.getObjectValue(prev);
        this.movePos(prevOffset);
        this.readSimpleXREF();
    }
};
EcmaBuffer.prototype.findFirstXREFOffset = function () {
    var i = this.data.length - 10;
    while (i > 0) {
        var ch = this.data[i];
        if (ch === 0x73
                && this.data[i + 1] === 0x74
                && this.data[i + 2] === 0x61
                && this.data[i + 3] === 0x72
                && this.data[i + 4] === 0x74
                && this.data[i + 5] === 0x78
                && this.data[i + 6] === 0x72
                && this.data[i + 7] === 0x65
                && this.data[i + 8] === 0x66) {
            this.movePos(i);
            this.skipLine();
            var offset = this.getObj();
            return offset;
        }
        i--;
    }
    return -1;
};
EcmaBuffer.prototype.updateAllObjStm = function () {
    for (ee in EcmaOBJECTOFFSETS) {
        var arr = ee.split(" ");
        var tref = new EcmaOREF(arr[0], arr[1]);
        var dd = this.getIndirectObject(tref);
        if (dd instanceof EcmaDICT && dd.keys[EcmaKEY.Type]
                && dd.keys[EcmaKEY.Type].name === EcmaKEY.ObjStm) {
            var nn = dd.keys[EcmaKEY.N];
            var first = dd.keys[EcmaKEY.First];
            var subBuf = new EcmaBuffer(dd.stream);
            for (var i = 0; i < nn; i++) {
                var id = subBuf.getNumberValue();
                subBuf.getByte();
                var offset = subBuf.getNumberValue();
                subBuf.getByte();
                var entryRef = id + " " + 0 + " R";
                var objoff = new EcmaOBJOFF((first + offset), dd.stream, true);
                if (entryRef in EcmaOBJECTOFFSETS) {
                    if (EcmaOBJECTOFFSETS[entryRef].isStream && !EcmaOBJECTOFFSETS[entryRef].data) {
                        EcmaOBJECTOFFSETS[entryRef] = objoff;
                    }
                } else {
                    EcmaOBJECTOFFSETS[entryRef] = objoff;
                }
            }
        }
    }
};
EcmaBuffer.prototype.updatePageOffsets = function () {
    var mc = this.getObjectValue(EcmaMainCatalog);
    var pageTree = mc.keys[EcmaKEY.Pages];
    if (pageTree) {
        pageTree = this.getObjectValue(pageTree);
        this.getPagesFromPageTree(pageTree);
    }
};
EcmaBuffer.prototype.getPagesFromPageTree = function (pageTreeDict) {
    var kids = pageTreeDict.keys[EcmaKEY.Kids];
    kids = this.getObjectValue(kids);
    for (var i = 0, ii = kids.length; i < ii; i++) {
        var kRef = kids[i];
        var kDict = this.getObjectValue(kRef);
        var type = kDict.keys[EcmaKEY.Type];
        if (type.name === EcmaKEY.Pages) {
            this.getPagesFromPageTree(kDict);
        } else if (type.name === EcmaKEY.Page) {
            EcmaPageOffsets.push(kRef);
        }
    }
};

var EcmaParser = {
    saveFormToPDF: function (fileName) {
        var dataArr = this._insertFieldsToPDF(fileName);
        this._openURL(fileName, dataArr);
    },
    _insertFieldsToPDF: function (fileName) {
        this._updateFileInfo(fileName);
        var buff = new EcmaBuffer(EcmaMainData);
        var prev = buff.findFirstXREFOffset();
        if (prev) {
            buff.movePos(prev);
            buff.readSimpleXREF();
        }
        buff.updateAllObjStm();
        buff.updatePageOffsets();
        var maxUsed = 1;
        for (var nn in EcmaOBJECTOFFSETS) {
            var mm = nn.split(" ");
            maxUsed = Math.max(parseInt(mm[0]), maxUsed);
        }
        maxUsed++;

        var fieldDicts = [];
        var fieldRefs = [];

        var ecmaMainDict = buff.getObjectValue(EcmaMainCatalog);
        var acroformRef = ecmaMainDict.keys[EcmaKEY.AcroForm];
        var acroform = buff.getObjectValue(acroformRef);
        delete acroform.keys[EcmaKEY.XFA];

        fieldDicts.push(acroform);
        fieldRefs.push(acroformRef);

        var inputs = document.getElementsByTagName("input");
        var textareas = document.getElementsByTagName("textarea");
        var selects = document.getElementsByTagName("select");

        var texts = [];
        var checks = [];
        var radios = [];
        var choices = [];

        for (var i = 0, ii = inputs.length; i < ii; i++) {
            var inp = inputs[i];
            var ref = inp.getAttribute("data-objref");
            if (ref && ref.length > 0) {
                var type = inp.type.toUpperCase();
                if (type === "TEXT" || type === "PASSWORD") {
                    texts.push(inp);
                } else if (type === "CHECKBOX") {
                    checks.push(inp);
                } else if (type === "RADIO") {
                    radios.push(inp);
                }
            }
        }
        for (var i = 0, ii = textareas.length; i < ii; i++) {
            var inp = textareas[i];
            var ref = inp.getAttribute("data-objref");
            if (ref && ref.length > 0) {
                texts.push(inp);
            }
        }
        for (var i = 0, ii = selects.length; i < ii; i++) {
            var inp = selects[i];
            var ref = inp.getAttribute("data-objref");
            if (ref && ref.length > 0) {
                choices.push(inp);
            }
        }

        for (var i = 0, ii = texts.length; i < ii; i++) {
            var fieldText = texts[i].value;
            var refStr = texts[i].getAttribute("data-objref");
            var fieldRef = EcmaLEX.getRefFromString(refStr);
            var fieldDict = buff.getObjectValue(fieldRef);
            fieldDicts.push(fieldDict);
            fieldRefs.push(fieldRef);
            EcmaFormField.editTextField(fieldDicts, fieldRefs, fieldDict, fieldText, maxUsed);
            maxUsed++;
        }

        for (var i = 0, ii = checks.length; i < ii; i++) {
            var curSelect = checks[i].checked;
            var refStr = checks[i].getAttribute("data-objref");
            var fieldRef = EcmaLEX.getRefFromString(refStr);
            var fieldDict = buff.getObjectValue(fieldRef);
            fieldDicts.push(fieldDict);
            fieldRefs.push(fieldRef);
            EcmaFormField.selectCheckBox(curSelect, fieldDict);
        }

        for (var i = 0, ii = choices.length; i < ii; i++) {
            var selected = choices[i].value;
            var refStr = choices[i].getAttribute("data-objref");
            var fieldRef = EcmaLEX.getRefFromString(refStr);
            var fieldDict = buff.getObjectValue(fieldRef);
            fieldDicts.push(fieldDict);
            fieldRefs.push(fieldRef);
            EcmaFormField.selectChoice(fieldDicts, fieldRefs, fieldDict, selected, maxUsed);
            maxUsed++;
        }

        var radiosObj = {};
        for (var i = 0, ii = radios.length; i < ii; i++) {
            var radio = radios[i];
            var refStr = radio.getAttribute("data-objref");
            var fieldRef = EcmaLEX.getRefFromString(refStr);
            var fieldDict = buff.getObjectValue(fieldRef);
            var parRefStr = fieldDict.keys[EcmaKEY.Parent].ref;
            var isChecked = radio.checked;
            var value = radio.value;
            if (parRefStr) {
                if (parRefStr in radiosObj) {
                    radiosObj[parRefStr].push({radioRef: refStr, parentRef: parRefStr,
                        checked: isChecked, value: value});
                } else {
                    radiosObj[parRefStr] = [{radioRef: refStr, parentRef: parRefStr,
                            checked: isChecked, value: value}];
                }
            } else {
                radiosObj[refStr] = [{radioRef: refStr, parentRef: null,
                        checked: isChecked, value: value}];
            }
        }

        for (var pp in radiosObj) {
            var arr = radiosObj[pp];
            var parRefStr = arr[0].parentRef;
            if (parRefStr) {
                var parentRef = EcmaLEX.getRefFromString(parRefStr);
                var parentDict = buff.getObjectValue(parentRef);
                fieldRefs.push(parentRef);
                fieldDicts.push(parentDict);

                var hasSelection = false;
                var curValue = null;

                for (var i = 0, ii = arr.length; i < ii; i++) {
                    if (arr[i].checked) {
                        curValue = arr[i].value;
                        hasSelection = true;
                        break;
                    }
                }

                if (hasSelection) {
                    parentDict.keys[EcmaKEY.V] = new EcmaNAME(curValue);
                } else {
                    delete parentDict.keys[EcmaKEY.V];
                }

                for (var i = 0, ii = arr.length; i < ii; i++) {
                    var radioRef = EcmaLEX.getRefFromString(arr[i].radioRef);
                    var radioDict = buff.getObjectValue(radioRef);
                    fieldRefs.push(radioRef);
                    fieldDicts.push(radioDict);
                    EcmaFormField.selectRadioChild(arr[i].checked, radioDict);
                }
            } else {
                var radioRef = EcmaLEX.getRefFromString(arr[i].radioRef);
                var radioDict = buff.getObjectValue(radioRef);
                fieldRefs.push(radioRef);
                fieldDicts.push(radioDict);
                EcmaFormField.selectRadioChild(arr[i].checked, radioDict);
            }
        }
        this._saveFieldObjects(prev, maxUsed, fieldRefs, fieldDicts);
        return EcmaMainData;
    },
    _saveFieldObjects: function (prev, maxUsed, fieldRefs, fieldDicts) {

        var objOff = EcmaMainData.length;
        var fieldOffsets = [];

        for (var i = 0, ii = fieldRefs.length; i < ii; i++) {
            var fieldRef = fieldRefs[i].num;
            var fieldDict = fieldDicts[i];
            fieldOffsets.push({ref: fieldRef, offset: objOff});
            var dictBytes = [];
            if (fieldDict.keys[EcmaKEY.Length]) {

                var startBytes = EcmaLEX.textToBytes(fieldRef + " 0 obj\n");
                var midBytes = EcmaLEX.textToBytes(EcmaLEX.objectToText(fieldDicts[i]) + "stream\n");
                var streamBytes = fieldDicts[i].rawStream;
                var endBytes = EcmaLEX.textToBytes("\nendstream\nendobj\n");

                EcmaLEX.pushBytesToBuffer(startBytes, dictBytes);
                EcmaLEX.pushBytesToBuffer(midBytes, dictBytes);
                EcmaLEX.pushBytesToBuffer(streamBytes, dictBytes);
                EcmaLEX.pushBytesToBuffer(endBytes, dictBytes);
                EcmaLEX.pushBytesToBuffer(dictBytes, EcmaMainData);

            } else {
                var dictionaryStr = fieldRef + " 0 obj\n" + EcmaLEX.objectToText(fieldDicts[i]) + "\nendobj\n";
                var dictBytes = EcmaLEX.textToBytes(dictionaryStr);
                EcmaLEX.pushBytesToBuffer(dictBytes, EcmaMainData);
            }
            objOff += dictBytes.length;
        }

        var current = EcmaMainData.length; //current xref offset
        if (EcmaXRefType) {
            var indexStr = "[";
            var wBytes = [];
            for (var i = 0, ii = fieldOffsets.length; i < ii; i++) {
                var ref = fieldOffsets[i].ref;
                var offset = fieldOffsets[i].offset;
                wBytes.push(1);
                EcmaLEX.pushBytesToBuffer(EcmaLEX.toBytes32(offset), wBytes);
                wBytes.push(0);
                indexStr += ref + " 1 ";
            }
            indexStr += "]";

            var size = maxUsed;
            var dictStr = size + " 0 obj\n<</Type /XRef /Root " + EcmaMainCatalog.ref
                    + " /Prev " + prev + " /Index " + indexStr + " /W [1 4 1] /Size " + size
                    + "/Length " + wBytes.length + ">>stream\n";
            EcmaLEX.pushBytesToBuffer(EcmaLEX.textToBytes(dictStr), EcmaMainData);
            EcmaLEX.pushBytesToBuffer(wBytes, EcmaMainData);
            dictStr = "\nendstream\nendobj\nstartxref\n" + current + "\n%%EOF";
            EcmaLEX.pushBytesToBuffer(EcmaLEX.textToBytes(dictStr), EcmaMainData);
        } else {
            EcmaLEX.pushBytesToBuffer([120, 114, 101, 102, 0xa], EcmaMainData);
            var temp = "";
            for (var i = 0, ii = fieldOffsets.length; i < ii; i++) {
                var fieldRef = fieldOffsets[i].ref;
                var fieldOffset = fieldOffsets[i].offset;
                temp += fieldRef + " 1\n" + EcmaLEX.getZeroLead(fieldOffset) + " 00000 n \n";
            }
            var size = maxUsed;
            temp += "trailer\n<</Size " + size + " /Root " + EcmaMainCatalog.ref + " /Prev " + prev + ">>\n";
            temp += "startxref\n" + current + "\n%%EOF";
            EcmaLEX.pushBytesToBuffer(EcmaLEX.textToBytes(temp), EcmaMainData);
        }
    },
    saveAnnotationToPDF: function (fileName, annotArr) {
        this._updateFileInfo(fileName);
        var buff = new EcmaBuffer(EcmaMainData);
        var prev = buff.findFirstXREFOffset();
        if (prev) {
            buff.movePos(prev);
            buff.readSimpleXREF();
        }
        buff.updateAllObjStm();
        buff.updatePageOffsets();
        var maxRef = 1;
        for (var nn in EcmaOBJECTOFFSETS) {
            var mm = nn.split(" ");
            maxRef = Math.max(parseInt(mm[0]), maxRef);
        }
        maxRef++;
        this._saveAnnotObjects(fileName, prev, maxRef, annotArr);

    },
    _updateFileInfo: function (fileName) {
        EcmaNAMES = {};
        EcmaOBJECTOFFSETS = {};
        EcmaPageOffsets = [];
        EcmaMainCatalog = null;        
        EcmaXRefType = 0; //zero for old and 1 for stream;
        var dd = document.getElementById("FDFXFA_PDFDump");
        if (dd) {
            EcmaMainData = EcmaFilter.decodeBase64(dd.textContent);
        } else {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
                EcmaMainData = [];
                if (xhttp.readyState === 4 && xhttp.status === 200) {
                    var str = xhttp.responseText;
                    for (var i = 0, ii = str.length; i < ii; i++) {
                        EcmaMainData.push(str.charCodeAt(i) & 0xff);
                    }
                    return;
                }
            };
            xhttp.open("GET", fileName, false);
            xhttp.overrideMimeType('text\/plain; charset=x-user-defined');
            xhttp.send();
        }
    },
    _saveAnnotObjects: function (fileName, prev, maxUsed, annotArr) {

        var objRef = maxUsed;
        var objOff = EcmaMainData.length;
        var annotOffsets = [];
        var pageOffsetsObj = {};
        var pageDictsObj = {};

        var tBuff = new EcmaBuffer(EcmaMainData);

        for (var i = 0, ii = annotArr.length; i < ii; i++) {

            var pageNum = annotArr[i].page;
            var pageNumStr = "" + pageNum;

            var page = EcmaPageOffsets[pageNum];
            var pageDict;
            if (pageNumStr in pageDictsObj) {
                pageDict = pageDictsObj[pageNumStr];
            } else {

                pageDict = tBuff.getObjectValue(page);
                pageDictsObj[pageNumStr] = pageDict;
            }

            var annotName = pageDict.keys[EcmaKEY.Annots];

            pageOffsetsObj[pageNumStr] = page.num;

            annotOffsets.push({ref: objRef, offset: objOff});
            var dictionaryStr = objRef + " 0 obj\n" + annotArr[i].getDictionaryString() + "\nendobj\n";
            var dictBytes = EcmaLEX.textToBytes(dictionaryStr);
            EcmaLEX.pushBytesToBuffer(dictBytes, EcmaMainData);
            if (annotName) {
                if (EcmaLEX.isRef(annotName)) {
                    var annotRef = tBuff.getObjectValue(annotName);
                    if (EcmaLEX.isArray(annotRef)) {
                        pageDict.keys[EcmaKEY.Annots] = [];
                        for (var j = 0, jj = annotRef.length; j < jj; j++) {
                            pageDict.keys[EcmaKEY.Annots].push(annotRef[j]);
                        }
                        pageDict.keys[EcmaKEY.Annots].push(new EcmaOREF(objRef, 0));
                    } else {
                        pageDict.keys[EcmaKEY.Annots] = [annotName];
                        pageDict.keys[EcmaKEY.Annots].push(new EcmaOREF(objRef, 0));
                    }

//                     pageDict.keys[EcmaKEY.Annots] = [new EcmaOREF(objRef, 0)];
                } else if (EcmaLEX.isArray(annotName)) {
                    annotName.push(new EcmaOREF(objRef, 0));
                } else {
                    pageDict.keys[EcmaKEY.Annots] = [new EcmaOREF(objRef, 0)];
                }
            } else {
                pageDict.keys[EcmaKEY.Annots] = [new EcmaOREF(objRef, 0)];
            }
            objOff += dictBytes.length;
            objRef++;
        }

        var curPageStart = EcmaMainData.length;
        for (var pn in pageOffsetsObj) {
            var pageRef = pageOffsetsObj[pn];
            pageOffsetsObj[pn] = {ref: pageRef, offset: curPageStart}; //now store page offset points 
            var pageDict = pageDictsObj[pn];
            var pageDictStr = pageRef + " 0 obj\n" + EcmaLEX.objectToText(pageDict) + "\nendobj\n";
            var dictBytes = EcmaLEX.textToBytes(pageDictStr);
            EcmaLEX.pushBytesToBuffer(dictBytes, EcmaMainData);
            curPageStart = EcmaMainData.length;
        }

        var current = EcmaMainData.length; //current xref offset
        if (EcmaXRefType) {
            this._generateStreamXREF(prev, current, maxUsed, annotOffsets, pageOffsetsObj);
        } else {
            this._generateSimpleXREF(prev, current, maxUsed, annotOffsets, pageOffsetsObj);
        }
        this._openURL(fileName);
    },
    _generateSimpleXREF: function (prev, current, maxUsed, annotOffsets, pageOffsetsObj) {
        EcmaLEX.pushBytesToBuffer([120, 114, 101, 102, 0xa], EcmaMainData);
        var temp = "";
        for (pn in pageOffsetsObj) {
            var pageRef = pageOffsetsObj[pn].ref;
            var pageOffset = pageOffsetsObj[pn].offset;
            temp += pageRef + " 1\n" + EcmaLEX.getZeroLead(pageOffset) + " 00000 n \n";
        }
        var totalAnnots = annotOffsets.length;
        if (totalAnnots) {
            temp += maxUsed + " " + totalAnnots + "\n";
            for (var i = 0, ii = totalAnnots; i < ii; i++) {
                temp += EcmaLEX.getZeroLead(annotOffsets[i].offset) + " 00000 n \n";
            }
        }
        var size = maxUsed + totalAnnots;
        temp += "trailer\n<</Size " + size + " /Root " + EcmaMainCatalog.ref + " /Prev " + prev + ">>\n";
        temp += "startxref\n" + current + "\n%%EOF";
        EcmaLEX.pushBytesToBuffer(EcmaLEX.textToBytes(temp), EcmaMainData);

    },
    _generateStreamXREF: function (prev, current, maxUsed, annotOffsets, pageOffsetsObj) {
        var totalAnnots = annotOffsets.length;
        var indexStr = "[";
        var wBytes = [];
        for (var pn in pageOffsetsObj) {
            var ref = pageOffsetsObj[pn].ref;
            var offset = pageOffsetsObj[pn].offset;
            wBytes.push(1);
            EcmaLEX.pushBytesToBuffer(EcmaLEX.toBytes32(offset), wBytes);
            wBytes.push(0);
            indexStr += ref + " 1 ";
        }
        indexStr += maxUsed + " " + totalAnnots + "]";
        for (var i = 0; i < totalAnnots; i++) {
            var offset = annotOffsets[i].offset;
            wBytes.push(1);
            EcmaLEX.pushBytesToBuffer(EcmaLEX.toBytes32(offset), wBytes);
            wBytes.push(0);
        }
        var size = maxUsed + totalAnnots + 1;
        var dictStr = size + " 0 obj\n<</Type /XRef /Root " + EcmaMainCatalog.ref
                + " /Prev " + prev + " /Index " + indexStr + " /W [1 4 1] /Size " + size
                + "/Length " + wBytes.length + ">>stream\n";
        EcmaLEX.pushBytesToBuffer(EcmaLEX.textToBytes(dictStr), EcmaMainData);
        EcmaLEX.pushBytesToBuffer(wBytes, EcmaMainData);
        dictStr = "\nendstream\nendobj\nstartxref\n" + current + "\n%%EOF";
        EcmaLEX.pushBytesToBuffer(EcmaLEX.textToBytes(dictStr), EcmaMainData);

    },
    _openURL: function (fileName, dataArr) {
        var str = EcmaFilter.encodeBase64(dataArr);
        var hrefStr = "data:application/octet-stream;base64," + str;
        var title = fileName;
        var userAgent = "" + navigator.userAgent;
        if (userAgent.indexOf("Edge") !== -1 || userAgent.indexOf("MSIE ") !== -1) {
            var ab = new ArrayBuffer(dataArr.length);
            var ia = new Uint8Array(ab);
            for (var i = 0, ii = dataArr.length; i < ii; i++) {
                ia[i] = dataArr[i] & 0xff;
            }
            var blobObject = new Blob([ab], {type: "application/octet-stream"});
            window.navigator.msSaveBlob(blobObject, title);
            return;
        }
        var link = document.createElement("a");
        link.setAttribute("download", title);
        link.setAttribute("href", hrefStr);
        document.body.appendChild(link);
        if ("click" in link) {
            link.click();
        } else { //hack for safari
            var clk = document.createEvent("MouseEvent");
            clk.initEvent("click", true, true);
            link.dispatchEvent(clk);
        }
        link.setAttribute("href", "");
    }
};

//Example
function showExample() {

    var annot = new Annotation();
    annot.page = 0;
    annot.type = EcmaKEY.Link;
    annot.URI = "http://www.idrsolutions.com";
//    the quads here follow as per the spec - counter clockwise
//    annot.quads = [35,459,220,459,220,469,35,469];    
    annot.rect = [0, 0, 500, 500];
    annot.strokeColor = [1, 1, 0];
    annot.fillColor = [1, 1, 0];
//  annot.width = 0;
//  annot.opacity = 0.2;

    var annot2 = new Annotation();
    annot2.page = 0;
    annot2.type = EcmaKEY.Text;
    annot2.rect = [150, 500, 400, 600];
    annot2.contents = "This is text annotation";

    var annot3 = new Annotation();
    annot3.page = 0;
    annot3.type = EcmaKEY.Line;
    annot3.points = [600, 700, 400, 400];
    annot3.strokeColor = [0, 0, 0];
    annot3.rect = [600, 700, 400, 400];

    var annot4 = new Annotation();
    annot4.page = 0;
    annot4.type = EcmaKEY.Circle;
    annot4.strokeColor = [1, 0, 0];
    annot4.fillColor = [1, 0, 0];
    annot4.rect = [150, 150, 200, 200];

    var annot5 = new Annotation();
    annot5.page = 0;
    annot5.type = EcmaKEY.Square;
    annot5.strokeColor = [1, 0, 0];
    annot5.fillColor = [0.5, 1, 0.5];
    annot5.rect = [350, 150, 400, 200];

    var annot6 = new Annotation();
    annot6.page = 0;
    annot6.type = EcmaKEY.Ink;
    //gestures is important for Ink
    annot6.gestures = [[100, 500, 200, 400, 200, 200, 300, 300], [200, 200, 250, 200]];
    annot6.strokeColor = [0.2, 0.5, 0.7];
    annot6.fillColor = [0, 1, 0];
    annot6.rect = [0, 0, 500, 500];
    annot6.width = 4;

    var annot7 = new Annotation();
    annot7.page = 0;
    annot7.type = EcmaKEY.PolyLine; //or EcmaKEY.Polygon
    //vertices is important for polygon and polyline
    annot7.vertices = [0, 0, 0, 100, 100, 100, 100, 400];
    annot7.strokeColor = [0, 0, 1];
    annot7.fillColor = [0, 1, 0];
    annot7.rect = [0, 0, 500, 500];

    var annot8 = new Annotation();
    annot8.page = 0;
    annot8.type = EcmaKEY.Link;
    annot8.GoTo = "6 0 R";
    annot8.rect = [100, 100, 200, 200];
    annot8.strokeColor = [0, 0, 1];
    annot8.fillColor = [0, 1, 0.5];


    var annot9 = new Annotation();
    annot9.page = 0;
    annot9.type = EcmaKEY.FreeText;
    annot9.rect = [100, 600, 200, 700];
    annot9.strokeColor = [1, 1, 1];
    annot9.fillColor = [1, 1, 1];

    var richcontent = new RichContent();
    richcontent.text = "Hi there how are you there\n";
    richcontent.textColor = "#ff0000";
    richcontent.textSize = 8;

    var richcontent2 = new RichContent();
    richcontent2.text = "this another";
    richcontent2.textColor = "#0000ff";
    richcontent2.textSize = 22;

    annot9.richContents = [richcontent, richcontent2];

    var annot10 = new Annotation();
    annot10.page = 0;
    annot10.type = EcmaKEY.Highlight;
    //quad points go z order
    annot10.quads = [35, 459, 73, 459, 73, 469, 35, 469];
    annot10.rect = [0, 0, 400, 400];
    annot10.strokeColor = [1, 1, 0];

    EcmaParser.saveAnnotationToPDF("barclays.pdf", [annot]);
//    EcmaParser.saveFormToPDF("simpleradio2.pdf");
}

//        var ab = new ArrayBuffer(EcmaMainData.length);
//        var ia = new Uint8Array(ab);
//        for (var i = 0, ii = EcmaMainData.length; i < ii; i++) {
//            ia[i] = EcmaMainData[i] & 0xff;
//        }
//        var blob = new Blob([ab], {type: "application/octet-stream"});
//        var url = URL.createObjectURL(blob);
//        console.log(url);
//        window.open(url, '_blank', '');