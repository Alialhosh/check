<?php
namespace Algo26\IdnaConvert\Test;

use Algo26\IdnaConvert\Exception\AlreadyPunycodeException;
use Algo26\IdnaConvert\Exception\InvalidCharacterException;
use Algo26\IdnaConvert\Exception\InvalidIdnVersionException;
use Algo26\IdnaConvert\ToIdn;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Algo26\IdnaConvert\ToIdn
 */
class ToIdnTest extends TestCase
{
    /**
     * @dataProvider providerUtf8
     *
     * @throws AlreadyPunycodeException
     * @throws InvalidCharacterException
     * @throws InvalidIdnVersionException
     */
    public function testEncodeUtf8($decoded, $expectEncoded)
    {
        $idnaConvert = new ToIdn();
        $encoded = $idnaConvert->convert($decoded);

        $this->assertEquals(
            $expectEncoded,
            $encoded,
            sprintf(
                'Strings "%s" and "%s" do not match',
                $expectEncoded,
                $encoded
            )
        );
    }

    /**
     * @dataProvider providerUtf8Idna2003
     *
     * @throws AlreadyPunycodeException
     * @throws InvalidCharacterException
     * @throws InvalidIdnVersionException
     */
    public function testEncodeUtf8Idna2003($decoded, $expectEncoded)
    {
        $idnaConvert = new ToIdn(2003);
        $encoded = $idnaConvert->convert($decoded);

        $this->assertEquals(
            $expectEncoded,
            $encoded,
            sprintf(
                'Strings "%s" and "%s" do not match',
                $expectEncoded,
                $encoded
            )
        );
    }

    /**
     * @dataProvider providerEmailAddress
     *
     * @throws InvalidIdnVersionException
     */
    public function testEncodeEmailAddress($decoded, $expectEncoded)
    {
        $idnaConvert = new ToIdn(2008);
        $encoded = $idnaConvert->convertEmailAddress($decoded);

        $this->assertEquals(
            $expectEncoded,
            $encoded,
            sprintf(
                'Strings "%s" and "%s" do not match',
                $expectEncoded,
                $encoded
            )
        );
    }

    /**
     * @dataProvider providerUrl
     *
     * @throws InvalidIdnVersionException
     */
    public function testEncodeUrl($decoded, $expectEncoded)
    {
        $idnaConvert = new ToIdn(2008);
        $encoded = $idnaConvert->convertUrl($decoded);

        $this->assertEquals(
            $expectEncoded,
            $encoded,
            sprintf(
                'Strings "%s" and "%s" do not match',
                $expectEncoded,
                $encoded
            )
        );
    }

    public function providerUtf8()
    {
        return [
            ['', ''],
            ['dass.example', 'dass.example'],
            ['m??ller', 'xn--mller-kva'],
            ['wei??enbach', 'xn--weienbach-i1a'],
            ['??????-??????', 'xn----9mcj9fole'],
            ['??????-??????', 'xn----2hckbod3a'],
            ['idndomain??a??o??uexample.example', 'xn--idndomainaouexample-owb39ane.example'],
            ['??ko.example', 'xn--ko-eka.example'],
            ['??????????.example', 'xn--6ca0bl71b4a.example'],
            ['????????????????????.example', 'xn--4cabegsede9b0e.example'],
            ['????????????????????.example', 'xn--d1abegsede9b0e.example'],
            ['3+1', '3+1'],
            ['www.b??ckerm??ller.example', 'www.xn--bckermller-q5a70a.example'],
            ['??', 'xn--cfa'],
            ['ek??is??zl??k', 'xn--ekiszlk-d1a0dy4d'],
            ['r??detforst??rref??rdselssikkerhed', 'xn--rdetforstrrefrdselssikkerhed-znc6bz8b'],
            ['ka??kavalc??.example', 'xn--kakavalc-0kb76b.example'],
            ['????.example', 'xn--uxan.example'],
            ['ksi??gowo????.example', 'xn--ksigowo-c5a1nq1a.example'],
            ['????????????????????????????????????.example', 'xn--80aebfcdsb1blidpdoq4e1i.example'],
            ['????????????.??????', 'xn--eqr31enth05q.xn--55qx5d'],
            ['??????????????.example', 'xn--1caqmypyo.example'],
            ['??????????????????.example', 'xn--1caqmypyo29d8i.example'],
            ['??????.example', 'xn--vk1bq81c.example'],
            ['????????????', 'xn--t-mfutbzh'],
            ['www.????????????????????????????????????.example', 'www.xn--clcul3aaa2lcuc4kf.example'],
            ['??????', 'xn--3e0b707e'],
            ['????????????.example', 'xn--xu5bx2sncw5i.example'],
            ['??????', 'xn--o39aa'],
            ['??????????-??????????.??????????.????????????????', 'xn----5gc8bsteqom5gm.xn--5dbik1ed.xn--9dbalbu5cfl'],
            ['??rjaj??zusk??nak', 'xn--rjajzusknak-r7a3h5b'],
            ['??????????????????', 'xn--q3cq3aix1l2a'],
            ['???????????????', 'xn--q3ca5bk4b5k'],
            ['chambres-dh??tes.example', 'xn--chambres-dhtes-bpb.example'],
            ['?????????????????????????????????.example', 'xn--72cba0e8bxb3cu4kb6d6b.example'],
            ['b??ren-m??gen-f??sse.example', 'xn--bren-mgen-fsse-5hb70axd.example'],
            ['da??.example', 'xn--da-hia.example'],
            ['d??m??in.example', 'xn--dmin-moa0i.example'],
            ['??aaa.example', 'xn--aaa-pla.example'],
            ['a??aa.example', 'xn--aaa-qla.example'],
            ['aa??a.example', 'xn--aaa-rla.example'],
            ['aaa??.example', 'xn--aaa-sla.example'],
            ['d??j??.vu.example', 'xn--dj-kia8a.vu.example'],
            ['efra??n.example', 'xn--efran-2sa.example'],
            ['??and??.example', 'xn--and-6ma2c.example'],
            ['Foo.??Bcd??f.example', 'Foo.xn--bcdf-9na9b.example'],
            ['????????.??????????-??????????????????.??????', 'xn--4gbrim.xn----ymcbaaajlc6dj7bxne2c.xn--wgbh1c'],
            ['fu??ball.example', 'xn--fuball-cta.example'],
            ['????????18??????????', 'xn--18-uldcat6ad6bydd'],
            ['??????18??????????', 'xn--18-dtd1bdi0h3ask'],
        ];
    }

    public function providerUtf8Idna2003()
    {
        return [
            ['da??.example', 'dass.example'],
            ['dass.example', 'dass.example'],
            ['M??ller', 'xn--mller-kva'],
            ['wei??enbach', 'weissenbach'],
            ['???.example', 'xn--n3h.example'],
            ['fu??ball.example', 'fussball.example'],
        ];
    }

    public function providerEmailAddress()
    {
        return [
            ['some.user@????????????????????.example', 'some.user@xn--d1abegsede9b0e.example'],
            ['some.user@????.example', 'some.user@xn--uxan.example'],
            ['s??me.??ser@da??.example', 's??me.??ser@xn--da-hia.example'],
            ['some.user@foo.??bcd??f.example', 'some.user@foo.xn--bcdf-9na9b.example'],
        ];
    }

    public function providerUrl()
    {
        return [
            [
                'https://user:password@????????????????????.example/home/international/test.html',
                'https://user:password@xn--d1abegsede9b0e.example/home/international/test.html'
            ],
            [
                'https://??ser:p????word@????.example/gn??rz/l??rz/',
                'https://??ser:p????word@xn--uxan.example/gn??rz/l??rz/'
            ],
            [
                'https://user:password@da??.example/',
                'https://user:password@xn--da-hia.example/'
            ],
            [
                'https://user:password@foo.??bcd??f.example',
                'https://user:password@foo.xn--bcdf-9na9b.example'
            ],
            [
                'http://??and??.example',
                'http://xn--and-6ma2c.example'
            ],
            [
                'file:///some/path/s??mewhere/',
                'file:///some/path/s??mewhere/'
            ],
        ];
    }
}
